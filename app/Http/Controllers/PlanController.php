<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\User;
use App\Models\PlanOrder;
use App\Models\Utility;
use File;
use Illuminate\Http\Request;
use Nwidart\Modules\Facades\Module;

class PlanController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('manage plan')) {
            \App::setLocale(\Auth::user()->currentLanguage());
            $users = \Auth::user();
            $currantLang = $users->currentLanguage();
            $modules=[];
            $modules = Module::getByStatus(1);
            
            if(\Auth::user()->type=='company')
            {
                $plans = Plan::where('is_plan_enable','on')->get();
            }else
            {
                $plans = Plan::get();
            }
            $admin_payment_setting = Utility::getAdminPaymentSetting();

            return view('plan.index', compact('plans', 'admin_payment_setting', 'currantLang', 'users','modules'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }
    public function create()
    {
        $arrDuration = [
            'Lifetime' => __('Lifetime'),
            'Month' => __('Per Month'),
            'Year' => __('Per Year'),
        ];
        $modules = Module::getByStatus(1);

        return view('plan.create', compact('arrDuration','modules'));
    }
    public function store(Request $request)
    {
        
        $admin_payment_setting = Utility::getAdminPaymentSetting();

        $paymentIsOn=utility::getPaymentIsOn();
        if ($paymentIsOn) {
            $post = $request->all();
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|unique:plans',
                    'duration' => 'required',
                    'price' => 'required',
                    'themes' => 'required',
                    'business' => 'required',
                    'max_users' => 'required',
                    'storage_limit' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            if ($request->has('themes')) {
                $post['themes'] = implode(',', $request->themes);
            }
            if (!isset($request->enable_custdomain)) {
                $post['enable_custdomain'] = 'off';
            }
            if (!isset($request->enable_custsubdomain)) {
                $post['enable_custsubdomain'] = 'off';
            }
            if (!isset($request->enable_branding)) {
                $post['enable_branding'] = 'off';
            }
            if (!isset($request->pwa_business)) {
                $post['pwa_business'] = 'off';
            }
           
            if (!isset($request->enable_chatgpt)) {
                $post['enable_chatgpt'] = 'off';
            }
            if (!isset($request->is_trial)) {
                $post['is_trial'] = 'off';
            }
            if (!isset($request->trial_day)) {
                $post['trial_day'] = 0;
            }

            $post['module'] = !empty($request->modules) ? implode(',',$request->modules) : '';
            if (Plan::create($post)) {
                return redirect()->back()->with('success', __('Plan Successfully created.'));
            } else {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        } else {
            return redirect()->back()->with('error', __('Please set proper payment setting for add new plan.'));
        }

    }
    public function show($id)
    {
        return redirect()->back();  
    }
    public function edit($plan_id)
    {
        $arrDuration = [
            'Lifetime' => __('Lifetime'),
            'Month' => __('Per Month'),
            'Year' => __('Per Year'),
        ];
        $plan = Plan::find($plan_id);
        $modules = Module::getByStatus(1);
        return view('plan.edit', compact('plan', 'arrDuration','modules'));
    }


    public function update(Request $request, $plan_id)
    {


        $admin_payment_setting = Utility::getAdminPaymentSetting();

        $paymentIsOn=utility::getPaymentIsOn();
        if ($paymentIsOn) {
            $plan = Plan::find($plan_id);
            if (!empty($plan)) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|unique:plans,name,' . $plan_id,
                        'duration' => 'required',
                        'themes' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }
                $post = $request->all();
                if ($request->has('themes')) {
                    $post['themes'] = implode(',', $request->themes);
                }
                if (!isset($request->enable_custdomain)) {
                    $post['enable_custdomain'] = 'off';
                }
                if (!isset($request->enable_custsubdomain)) {
                    $post['enable_custsubdomain'] = 'off';
                }
                if (!isset($request->enable_branding)) {
                    $post['enable_branding'] = 'off';
                }
                if (!isset($request->pwa_business)) {
                    $post['pwa_business'] = 'off';
                }
                
                if (!isset($request->enable_chatgpt)) {
                    $post['enable_chatgpt'] = 'off';
                }
                if (!isset($request->is_trial)) {
                    $post['is_trial'] = 'off';
                    $post['trial_day'] = 0;
                }
                $post['module'] = !empty($request->modules) ? implode(',',$request->modules) : '';

                if ($plan->update($post)) {
                    return redirect()->back()->with('success', __('Plan successfully updated.'));
                } else {
                    return redirect()->back()->with('error', __('Something is wrong.'));
                }
            } else {
                return redirect()->back()->with('error', __('Plan not found.'));
            }
        } else {
            return redirect()->back()->with('error', __('Please set stripe api key & secret key for add new plan.'));
        }
    }

    public function userPlan(Request $request)
    {
        $objUser = \Auth::user();

        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->code);
        $plan = Plan::find($planID);

        if ($plan) {
            if ($plan->price <= 0) {
                $objUser->assignPlan($plan->id);

                return redirect()->route('plans.index')->with('success', __('Plan successfully activated.'));
            } else {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        } else {
            return redirect()->back()->with('error', __('Plan not found.'));
        }
    }
    public function payment($code)
    {
        if (\Auth::user()->can('buy plan')) {
            try {
                $planID = \Illuminate\Support\Facades\Crypt::decrypt($code);
                $plan = Plan::find($planID);
                if ($plan) {
                    return redirect()->route('stripe', compact('code'))->with('error', __('Your Payment has failed!'));
                } else {
                    return redirect()->back()->with('error', __('Plan is deleted.'));
                }
            } catch (\Throwable $th) {
                return redirect()->route('plans.index')->with('error', __('Plan not found!'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function trialPeriod(Request $request)
    {
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->code);
        $objUser = \Auth::user();
        $plan = Plan::find($planID);

        $objUser->assignPlan($plan->id);
        $objUser->trial_expire_date = now()->addDays($plan->trial_day);
        $objUser->is_trial_plan = $planID;
        $objUser->save();
        return redirect()->back()->with('success', __('Congratulations! You can now enjoy trial for your subscription.'));

    }

    public function destroy($id)
    {
            $plan = Plan::find($id);
            if ($plan) {
                $users=User::where('plan',$id)->get();
               
                if(count($users)==0)
                {
                    $plan->delete();
                return redirect()->route('plans.index')->with('success', __('Plan successfully deleted .'));    
                }
                else
                {
                    return redirect()->back()->with('error',__('The company has subscribed to this plan, so it cannot be deleted.'));
                }
                
            } else {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
    }

    public function planEnable(Request $request)
    {
        $data = [];
        $plan = plan::find($request->id);
        $users=User::where('plan',$request->id)->get();
        if(count($users)==0)
        {
            if ($request->is_disable == 1) {
                $plan->is_plan_enable = 'on';
                $data['msg']='Plan is enable.';
            } else {
                $plan->is_plan_enable = 'off';
                $data['msg']='Plan is disable.';
            }
            $plan->save();
            $data['is_success'] = true;
            
        }else{
            $data['msg']='The company has subscribed to this plan, so it cannot be disable.';
            $data['is_success'] = false;
        }
        return $data;
    }

    public function refundPlan(Request $request, $order_id, $user_id)
    {   

        $objUser = User::find($user_id);
        PlanOrder::where('id', $order_id)->update(['is_refund' => 1]);
        $objUser->assignPlan(1);
        $objUser->save();

        return redirect()->back()->with('success', __('We successfully planned a refund and assigned a free plan.'));
    }
}
