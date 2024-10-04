<?php

namespace App\Http\Controllers;

use App\Models\CostSetting;
use Illuminate\Http\Request;
use App\Models\Campaigns;
use App\Models\BusinessCategory;
use App\Models\Business;
use App\Models\Utility;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use DateTime;
use DateInterval;
use DatePeriod;
use Illuminate\Http\RedirectResponse;
use App\Models\Coupon;
use App\Models\UserCoupon;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class CampaignsController extends Controller
{
    public function index(Request $request)
    {
        $campaignsData = Campaigns::with('categories')->with('users');

        if (!empty($request->category)) {
            $campaignsData->where('category', $request->cat_type);
        }
        if (!empty($request->business)) {
            $campaignsData->where('business', $request->business);
        }
      
        if (!empty($request->start_date)) {
            $campaignsData->where('start_date', '>=', $request->start_date);
        }
        if (!empty($request->end_date)) {
            $campaignsData->where('end_date', '<=', $request->end_date);
        }
        if (Auth::user()->type == 'company') {
            $campaignsData = $campaignsData->where('created_by', Auth::user()->creatorId())->get();
        } else {
            $campaignsData = $campaignsData->get();
        }

        $currentDate = now();
        foreach ($campaignsData as $campaign) {
            if ($currentDate->greaterThan($campaign->end_date)) {
                $campaign->status = 2;
                $campaign->save();
            }
        }
        // Extract all business IDs
        // $allBusinessIds = [];
        // foreach ($campaignsData as $campaign) {
        //     if (!empty($campaign->business)) {
        //         $businessIds = explode(',', $campaign->business);
        //         $allBusinessIds = array_merge($allBusinessIds, $businessIds);
        //     }
        // }
        // $allBusinessIds = array_unique(array_filter($allBusinessIds));
        // $businessTitles = Business::whereIn('id', $allBusinessIds)->get(['id', 'title', 'slug'])->keyBy('id');
        $businessList = Business::get()->pluck('title', 'id');
        $businessList->prepend('Select Business');
        $catList = BusinessCategory::get()->pluck('name', 'id');
        $catList->prepend('Select Category');
        $userList = User::get()->pluck('name', 'id');
        $userList->prepend('Select User');
        return view('campaigns.index', compact('campaignsData', 'businessList', 'catList', 'userList'));



    }
    public function create()
    {
        $category = BusinessCategory::get()->pluck('name', 'id');
        $category->prepend('Select Category');
        return view('campaigns.create', compact('category'));


    }
    public function businessData(Request $request)
    {

        $businesses = Business::where('created_by', \Auth::user()->creatorId())->where('business_category', $request->category_id)->where('status', 'active')->get()->pluck('title', 'id')->toArray();
        return response()->json($businesses);
    }
    public function store(Request $request)
    {

        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'category' => 'required',
                'business' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        

        $campaignData = Campaigns::where('business',$request->business)->where('created_by',\Auth::user()->creatorId())->get();
       
        if($campaignData)
        {
            foreach ($campaignData as $key => $value) {

                if (
                    ($request->start_date >= $value->start_date && $request->start_date <= $value->end_date) ||
                    ($request->end_date >= $value->start_date && $request->end_date <= $value->end_date) ||
                    ($request->start_date <= $value->start_date && $request->end_date >= $value->end_date)
                ) {
                    return redirect()->back()->with('error', 'The Campaign dates overlap with an existing campaign for this business.');
                }
            }
        }
        
        

        $amount = $request->total_cost;
        $admin_payment_setting = Utility::getAdminPaymentSetting();
        $admin_currency = !empty($admin_payment_setting['CURRENCY']) ? $admin_payment_setting['CURRENCY'] : 'USD';


        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $objUser = \Auth::user();
        $product = $request->name;
        $code = '';
        if ($request->payment_method == 'Stripe') {
            /* Final price */
            $stripe_formatted_price = in_array(
                $admin_currency,
                [
                    'MGA',
                    'BIF',
                    'CLP',
                    'PYG',
                    'DJF',
                    'RWF',
                    'GNF',
                    'UGX',
                    'JPY',
                    'VND',
                    'VUV',
                    'XAF',
                    'KMF',
                    'KRW',
                    'XOF',
                    'XPF',
                ]
            ) ? number_format($amount, 2, '.', '') : number_format($amount, 2, '.', '') * 100;
            $return_url_parameters = function ($return_type) {
                return '&return_type=' . $return_type . '&payment_processor=stripe';
            };
            /* Initiate Stripe */
            \Stripe\Stripe::setApiKey($admin_payment_setting['stripe_secret']);

            $stripe_session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => $admin_currency,
                            'product_data' => [
                                'name' => $product,
                                'description' => $product,
                            ],
                            'unit_amount' => $stripe_formatted_price,
                        ],
                        'quantity' => 1,
                    ],
                ],
                'metadata' => [
                    'user_id' => $objUser->id,
                    'package_id' => $request->campaignId,
                    'code' => $code,
                ],
                'mode' => 'payment',
                'success_url' => route('promote.success', [
                    'campaign_id' => $request->campaignId,
                    'currency' => $admin_currency,
                    'amount' => $amount,
                    'coupon_id' => $request->coupon,
                    'data' => $request->all(),
                    $return_url_parameters('success'),
                ]),
                'cancel_url' => route('promote.success', [
                    'campaign_id' => $request->campaignId,
                    'currency' => $admin_currency,
                    'amount' => $amount,
                    'coupon_id' => $request->coupon,
                    $return_url_parameters('cancel'),
                ]),
            ]);


            $stripe_session = $stripe_session ?? false;

            try {
                return new RedirectResponse($stripe_session->url);
            } catch (\Exception $e) {
                return redirect()->route('campaigns.index')->with('error', __('Transaction has been failed!'));
            }
        } else {
            $paypalConfig=new PaypalController();
            $paypalConfig->paymentConfig();
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
                $paypalToken = $provider->getAccessToken();
                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "application_context" => [
                        "return_url" =>route('promote.success', [
                            'campaign_id' => $request->campaignId,
                            'currency' => $admin_currency,
                            'amount' => $amount,
                            'coupon_id' => $request->coupon,
                            'data' => $request->all(),
                            'return_type'=>'success',
                        ]),
                        "cancel_url" =>route('promote.success', [
                            'campaign_id' => $request->campaignId,
                            'currency' => $admin_currency,
                            'amount' => $amount,
                            'coupon_id' => $request->coupon,
                            'data' => $request->all(),
                            'return_type'=>'success',
                        ]),
                    ],
                    "purchase_units" => [
                        0 => [
                            "amount" => [
                                "currency_code" => Utility::getValByName('site_currency'),
                                "value" => $amount
                            ]
                        ]
                    ]
                ]);
                if (isset($response['id']) && $response['id'] != null) {
                    // redirect to approve href
                    foreach ($response['links'] as $links) {
                        if ($links['rel'] == 'approve') {
                            return redirect()->away($links['href']);
                        }
                    }
                    return redirect()
                        ->route('campaigns.index')
                        ->with('error', 'Something went wrong.');
                } else {
                    return redirect()
                        ->route('campaigns.index')
                        ->with('error', $response['message'] ?? 'Something went wrong.');
                }
        }
      

        return redirect()->back()->with('success', 'Campaigns successfully created.');
    }
    public function edit($id)
    {
        $category = BusinessCategory::get()->pluck('name', 'id');
        $campaigns = Campaigns::find($id);
        $business    = Business::where('business_category',$campaigns->cat_type)->where('created_by', \Auth::user()->creatorId())->get()->pluck('title','id');
        
        return view('campaigns.edit', compact('category', 'campaigns','business'));
    }
    public function update(Request $request, $id)
    {

        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'category' => 'required',
                'business' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }


        $campaignsData = Campaigns::find($id);
        $campaignsData->name = $request->name;
        $campaignsData->user = \Auth::user()->id;
        $campaignsData->cat_type = $request->category;
        $campaignsData->business = implode(',', $request->business);
        $campaignsData->start_date = $request->start_date;
        $campaignsData->end_date = $request->end_date;
        $campaignsData->total_days = $request->total_days;
        $campaignsData->created_by = \Auth::user()->creatorId();
        $campaignsData->save();


        return redirect()->back()->with('success', 'Campaigns successfully updated.');
    }

    public function destroy($id)
    {

        $campaigns = Campaigns::find($id);
        if ($campaigns) {
            
            $campaigns->delete();

            return redirect()->route('campaigns.index')->with('success', __('Campaigns Successfully Deleted .'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong.'));
        }

    }
    public function viewCampaigns($id)
    {

        $campaigns = Campaigns::find($id);
        $settings = Utility::getAdminPaymentSetting();
        $businessIds = explode(',', $campaigns->business);
        $allBusinessIds = [];
        $allBusinessIds = array_merge($allBusinessIds, $businessIds);
        
        $allBusinessIds = array_unique(array_filter($allBusinessIds));
        $businessTitles = Business::whereIn('id', $allBusinessIds)->get(['id', 'title', 'slug'])->keyBy('id');
        return view('campaigns.view', compact('campaigns','businessTitles'));
    }
    public function ChangeStatus($id, $response)
    {
        $campaigns = Campaigns::find($id);
        $campaigns->approval = $response;
        if ($response == 2) {
            $campaigns->status = 3;
        }
        $campaigns->save();
        return redirect()->route('campaigns.index')->with('success', __('Campaigns status updated successfully .'));

    }

    public function campaignsEnable(Request $request)
    {
        $data = [];
        $campaigns = Campaigns::find($request->id);
        

        if ($request->is_disable == 1) {
            $campaigns->status = 1;
            $data['msg'] = 'campaigns is active.';
        } else {
            $campaigns->status = 2;
            $data['msg'] = 'campaigns is expired.';
        }
        $campaigns->save();
        $data['is_success'] = true;
        return $data;
    }

    public function businessAnalytics(Request $request, $id)
    {
        $campaign = Campaigns::findOrFail($id);

    // Fetch the category name based on the campaign's category ID
    $categoryName = BusinessCategory::where('id', $campaign->category)->value('name');

    $devicearray = [
        'label' => [$categoryName],
        'total_days' => [$campaign->total_days],
        'total_cost' => [$campaign->total_cost]
    ];
        return view('campaigns.analytics', compact('campaign', 'devicearray'));

    }


    public function campaignsSetup()
    {
        $businessDetails = Business::get();
        $costDetail = CostSetting::get();
        return view('campaigns.setup', compact('businessDetails', 'costDetail'));
    }
    public function businessEnable(Request $request)
    {

        $businesses = $request->input('businesses', []);

        foreach ($businesses as $business) {
            $businessId = $business['id'];
            $adminEnable = isset($business['directory_status']) ? 'on' : 'off';

            // Update the business status in the database
            $businessModel = Business::find($businessId); // Assuming you have a Business model
            if ($businessModel) {
                $businessModel->directory_status = $adminEnable;
                $businessModel->save();
            }
        }

        return redirect()->back()->with('success', 'Business status updated successfully.');


    }
    public function WholesaleCost(Request $request)
    {
        $costSettings = $request->input('category_group');

        $deletedIds = array_filter(explode(',', $request->input('deleted_ids')), function ($id) {
            return filter_var($id, FILTER_VALIDATE_INT) !== false;
        });
        $creatorId = \Auth::user()->creatorId();

        // Delete the cost settings marked for deletion
        if (!empty($deletedIds)) {
            CostSetting::whereIn('id', $deletedIds)->delete();
        }

        // Update or create cost settings
        foreach ($costSettings as $costSetting) {
            $costSetting['created_by'] = $creatorId;

            if (isset($costSetting['id']) && $costSetting['id']) {
                // Update existing cost setting
                $existingCostSetting = CostSetting::find($costSetting['id']);
                if ($existingCostSetting) {
                    $existingCostSetting->update($costSetting);
                }
            } else {
                // Create new cost setting
                CostSetting::create($costSetting);
            }
        }
        return redirect()->route('campaigns.setup')->with('success', 'Cost settings saved successfully!');
    }

    public function costData(Request $request)
    {
        
        $days=$request->total_days;

        $costData = CostSetting::where('min', '<=', $days)
        ->where('max', '>=', $days)
        ->first();
        return response()->json([
            'costData' => $costData
        ]);
    }

    public function paymentSuccess(Request $request)
    {
        if ($request->return_type == 'cancel') {
            return redirect()->route('campaigns.index')->with('error', 'Something went wrong!');
        } else {
            $requestData = $request->data;
           
            $objUser = \Auth::user();
            $campaignsData = new Campaigns(); 
            $campaignsData->user = \Auth::user()->id;
            $campaignsData->name = $requestData['name'];
            $campaignsData->category = $requestData['category'];
            $campaignsData->business = $requestData['business'];
            $campaignsData->total_days = $requestData['total_days'];
            $campaignsData->total_cost = $requestData['total_cost'];
            $campaignsData->start_date = $requestData['start_date'];
            $campaignsData->end_date = $requestData['end_date'];
            $campaignsData->payment_method = $requestData['payment_method'];
            $campaignsData->created_by = \Auth::user()->creatorId();
            $campaignsData->save();

            
            if ($request->has('coupon_id') && $request->coupon_id != '') {
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                if (!empty($request->coupon_id)) {
                    $coupons = Coupon::where('code', strtoupper($request->coupon_id))->where('is_active', '1')->first();
                    
                    $userCoupon         = new UserCoupon();
                    $userCoupon->user   = $objUser->id;
                    $userCoupon->coupon = $coupons->id;
                    $userCoupon->order  = $orderID;
                    $userCoupon->save();

                    $usedCoupun = $coupons->used_coupon();
                    
                }
            }
            return redirect()->route('campaigns.index')->with('success', 'Campaign created successfully!');
        }

    }
}