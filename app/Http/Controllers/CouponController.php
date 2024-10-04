<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\User;
use App\Models\Plan;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Http\Request;
use App\Exports\CouponsExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class CouponController extends Controller
{

    public function index()
    {
        $coupons = Coupon::get();
        return view('coupon.index', compact('coupons'));
    }


    public function create()
    {
        $couponType = [
            'percentage' => __('Percentage'),
            'flat' => __('Flat'),
        ];
        return view('coupon.create', compact('couponType'));
    }


    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'discount' => 'required|numeric|max:100',
                'limit' => 'required|numeric',
                'manualCode' => 'unique:coupons,code' . ($request->input('manualCode') ? '|nullable' : ''),
                'autoCode' => 'unique:coupons,code' . ($request->input('autoCode') ? '|nullable' : ''),
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        if (empty($request->manualCode) && empty($request->autoCode)) {
            return redirect()->back()->with('error', 'Coupon code is required');
        }
        $coupon = new Coupon();
        $coupon->name = $request->name;
        $coupon->type = $request->type;
        $coupon->minimum_spend = $request->minimum_spend;
        $coupon->maximum_spend = $request->maximum_spend;
        $coupon->discount = $request->discount;
        $coupon->limit = $request->limit;
        $coupon->per_user_limit = $request->per_user_limit;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->is_active = $request->is_active;

        if (!empty($request->manualCode)) {
            $coupon->code = $request->manualCode;
        }

        if (!empty($request->autoCode)) {
            $coupon->code = $request->autoCode;
        }
        $coupon->save();

        return redirect()->route('coupons.index')->with('success', __('Coupon successfully created.'));

    }


    public function show(Coupon $coupon)
    {
        $userCoupons = UserCoupon::where('coupon', $coupon->id)->with('userDetail')->get();

        return view('coupon.view', compact('userCoupons'));
    }


    public function edit(Coupon $coupon)
    {
        $couponType = [
            'percentage' => __('Percentage'),
            'flat' => __('Flat'),
        ];
        return view('coupon.edit', compact('coupon', 'couponType'));
    }


    public function update(Request $request, Coupon $coupon)
    {

        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'discount' => 'required|numeric',
                'limit' => 'required|numeric',
                'code' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $coupon = Coupon::find($coupon->id);
        $coupon->name = $request->name;
        $coupon->type = $request->type;
        $coupon->minimum_spend = $request->minimum_spend;
        $coupon->maximum_spend = $request->maximum_spend;
        $coupon->discount = $request->discount;
        $coupon->limit = $request->limit;
        $coupon->per_user_limit = $request->per_user_limit;
        $coupon->expiry_date = $request->expiry_date;
        if (isset($request->is_active)) {
            $coupon->is_active = $request->is_active;
        }

        $coupon->save();

        return redirect()->route('coupons.index')->with('success', __('Coupon successfully updated.'));
    }


    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('coupons.index')->with('success', __('Coupon successfully deleted.'));
    }

    public function applyCouponPromote(Request $request)
    {

        if ($request->coupon != '') {
            $original_price = self::formatPrice($request->total_cost);
            $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();

            if (!empty($coupons)) {
                if (Carbon::now()->gt(Carbon::parse($coupons->expiry_date))) {
                    return response()->json([
                        'is_success' => false,
                        'final_price' => $original_price,
                        'price' => number_format($request->total_cost, \Utility::getValByName('decimal_number')),
                        'message' => __('This coupon code has expired.'),
                    ]);
                }
                $usedCoupun = $coupons->used_coupon();
                if ($coupons->limit == $usedCoupun) {
                    return response()->json([
                        'is_success' => false,
                        'final_price' => $original_price,
                        'price' => number_format($request->total_cost, \Utility::getValByName('decimal_number')),
                        'message' => __('This coupon code has reached its overall usage limit.'),
                    ]);
                }
                $user = \Auth::user();

                $perusedCoupun = $coupons->per_used_coupon($user->id, $coupons->id);
                if ($coupons->per_user_limit == $perusedCoupun) {
                    return response()->json([
                        'is_success' => false,
                        'final_price' => $original_price,
                        'price' => number_format($request->total_cost, \Utility::getValByName('decimal_number')),
                        'message' => __('This coupon code has reached its usage limit for you.'),
                    ]);
                }

                // Check minimum spend
                if ($request->total_cost < $coupons->minimum_spend) {
                    return response()->json([
                        'is_success' => false,
                        'final_price' => $original_price,
                        'price' => number_format($request->total_cost, \Utility::getValByName('decimal_number')),
                        'message' => __('The promote cost does not meet the minimum spend required for this coupon.'),
                    ]);
                }

                // Check maximum spend
                if ($request->total_cost > $coupons->maximum_spend) {
                    return response()->json([
                        'is_success' => false,
                        'final_price' => $original_price,
                        'price' => number_format($request->total_cost, \Utility::getValByName('decimal_number')),
                        'message' => __('The promote cost exceeds the maximum spend allowed for this coupon.'),
                    ]);
                }

                // Calculate discount
                if ($coupons->type == 'percentage') {
                    $discount_value = ($request->total_cost / 100) * $coupons->discount;
                } else {
                    $discount_value = $coupons->discount;
                }
                $promote_price = $request->total_cost - $discount_value;
                $price = self::formatPrice($promote_price);
                $discount_value = '-' . self::formatPrice($discount_value);

                return response()->json([
                    'is_success' => true,
                    'discount_price' => $discount_value,
                    'final_price' => $price,
                    'price' => number_format($promote_price, \Utility::getValByName('decimal_number')),
                    'message' => __('Coupon code has been applied successfully.'),
                ]);
            } else {
                return response()->json([
                    'is_success' => false,
                    'final_price' => $original_price,
                    'price' => number_format($request->total_cost, \Utility::getValByName('decimal_number')),
                    'message' => __('This coupon code is invalid or has expired.'),
                ]);
            }
        }
    }


    public function applyCoupon(Request $request)
    {

        $plan = Plan::find(\Illuminate\Support\Facades\Crypt::decrypt($request->plan_id));
        if ($plan && $request->coupon != '') {
            $original_price = self::formatPrice($plan->price);
            $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();

            if (!empty($coupons)) {

                if (Carbon::now()->gt(Carbon::parse($coupons->expiry_date))) {
                    return response()->json([
                        'is_success' => false,
                        'final_price' => $original_price,
                        'price' => number_format($plan->price, \Utility::getValByName('decimal_number')),
                        'message' => __('This coupon code has expired.'),
                    ]);
                }

                // Check per user limit
                $usedCoupun = $coupons->used_coupon();
                if ($coupons->limit == $usedCoupun) {
                    return response()->json([
                        'is_success' => false,
                        'final_price' => $original_price,
                        'price' => number_format($plan->price, \Utility::getValByName('decimal_number')),
                        'message' => __('This coupon code has reached its usage limit.'),
                    ]);
                }

                $user = \Auth::user();

                $perusedCoupun = $coupons->per_used_coupon($user->id, $coupons->id);

                if ($coupons->per_user_limit == $perusedCoupun) {
                    return response()->json([
                        'is_success' => false,
                        'final_price' => $original_price,
                        'price' => number_format($plan->price, \Utility::getValByName('decimal_number')),
                        'message' => __('This coupon code has reached its usage limit.'),
                    ]);
                }

                // Check minimum spend
                if ($plan->price < $coupons->minimum_spend) {
                    return response()->json([
                        'is_success' => false,
                        'final_price' => $original_price,
                        'price' => number_format($plan->price, \Utility::getValByName('decimal_number')),
                        'message' => __('The plan price does not meet the minimum spend required for this coupon.'),
                    ]);
                }

                // Check maximum spend
                if ($plan->price > $coupons->maximum_spend) {
                    return response()->json([
                        'is_success' => false,
                        'final_price' => $original_price,
                        'price' => number_format($plan->price, \Utility::getValByName('decimal_number')),
                        'message' => __('The plan price exceeds the maximum spend allowed for this coupon.'),
                    ]);
                }

                // Calculate discount
                if ($coupons->type == 'percentage') {
                    $discount_value = ($plan->price / 100) * $coupons->discount;
                } else {
                    $discount_value = $coupons->discount;
                }
                $plan_price = $plan->price - $discount_value;
                $price = self::formatPrice($plan_price);
                $discount_value = '-' . self::formatPrice($discount_value);

                return response()->json([
                    'is_success' => true,
                    'discount_price' => $discount_value,
                    'final_price' => $price,
                    'price' => number_format($plan_price, \Utility::getValByName('decimal_number')),
                    'message' => __('Coupon code has been applied successfully.'),
                ]);
            } else {
                return response()->json([
                    'is_success' => false,
                    'final_price' => $original_price,
                    'price' => number_format($plan->price, \Utility::getValByName('decimal_number')),
                    'message' => __('This coupon code is invalid or has expired.'),
                ]);
            }
        }
    }


    public function formatPrice($price)
    {
        $payment_setting = Utility::getAdminPaymentSetting();
        return $payment_setting['CURRENCY_SYMBOL'] . number_format($price, \Utility::getValByName('decimal_number'));
    }

    public function export()
    {
        return Excel::download(new CouponsExport, 'Coupons.xlsx');
    }
}
