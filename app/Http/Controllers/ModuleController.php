<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Nwidart\Modules\Facades\Module;
use App\Models\Utility;
use App\Models\Plan;
use App\Models\User;
use App\Models\userActiveModule;
use Auth;
use Artisan;
use Log;
use ZipArchive;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = Module::all();
        $module_path = Module::getPath();
        $category_wise_add_ons = json_decode(file_get_contents("https://demo.workdo.io/cronjob/vcard_addon.json"), true);
        $addOnsCount = count($category_wise_add_ons['add_ons']);
        return view('module.module', compact('modules', 'module_path', 'category_wise_add_ons', 'addOnsCount'));
    }

    public function enable(Request $request)
    {

        $module = Module::find($request->name);
        if (!empty ($module)) {
            \App::setLocale('en');

            if ($module->isEnabled()) {
                $check_child_module = $this->Check_Child_Module($module);
                if ($check_child_module == true) {
                    $module->disable();
                    return redirect()->back()->with('success', __('Module Disable Successfully!'));
                } else {
                    return redirect()->back()->with('error', __($check_child_module['msg']));
                }

            } else {
                $check_parent_module = $this->Check_Parent_Module($module);
                if ($check_parent_module['status'] == true) {

                    Artisan::call('module:migrate ' . $request->name);
                    Artisan::call('module:seed ' . $request->name);


                    $module->enable();
                    Artisan::call('module:migrate ' . $module);

                    return redirect()->back()->with('success', __('Module Enable Successfully!'));
                } else {
                    return redirect()->back()->with('error', __($check_parent_module['msg']));
                }

            }
        } else {
            return redirect()->back()->with('error', __('oops something wren wrong!'));
        }
    }

    public function Check_Child_Module($module)
    {
        $path = $module->getPath() . '/module.json';
        $json = json_decode(file_get_contents($path), true);
        $status = true;
        if (isset ($json['child_module']) && !empty ($json['child_module'])) {
            foreach ($json['child_module'] as $key => $value) {
                $child_module = Utility::module_is_active($value);
                if ($child_module == true) {
                    $module = Module::find($value);
                    $module->disable();
                    if ($module) {
                        $this->Check_Child_Module($module);
                    }
                }
            }
            return true;
        } else {
            return true;
        }
    }

    public function Check_Parent_Module($module)
    {
        $path = $module->getPath() . '/module.json';
        $json = json_decode(file_get_contents($path), true);
        $data['status'] = true;
        $data['msg'] = '';

        if (isset ($json['parent_module']) && !empty ($json['parent_module'])) {
            foreach ($json['parent_module'] as $key => $value) {
                $modules = implode(',', $json['parent_module']);
                $parent_module = Utility::module_is_active($value);
                if ($parent_module == true) {
                    $module = Module::find($value);
                    if ($module) {
                        $this->Check_Parent_Module($module);
                    }
                } else {
                    $data['status'] = false;
                    $data['msg'] = 'please activate this module ' . $modules;
                    return $data;
                }
            }
            return $data;
        } else {
            return $data;
        }
    }

    public function moduleList(Request $request)
    {
        $plan = Plan::find(\Auth::user()->plan);

        $admin_setting = Utility::settings();
        $payment_setting = Utility::getAdminPaymentSetting();

        $modules = Module::getByStatus(1);
        $purchaseds = [];
        $active_module = Utility::ActivatedModule();
        if (count($active_module) > 0) {
            foreach ($active_module as $key => $value) {
                if (array_key_exists($value, $modules) == true) {
                    $module = Module::find($value);
                    $path = $module->getPath() . '/module.json';
                    $json = json_decode(file_get_contents($path), true);
                    if (!isset ($json['display']) || $json['display'] == true) {
                        array_push($purchaseds, $modules[$value]);
                    }
                    unset($modules[$value]);
                }
            }
        }
        return view('plan.marketplace', compact('modules', 'purchaseds', 'plan', 'admin_setting', 'payment_setting'));
    }

    public function addModuleActive(Request $request)
    {

        userActiveModule::create([
            'user_id' => Auth::user()->id,
            'module' => $request->module_name,
        ]);
        return true;
    }

    public function CancelAddOn($name = null)
    {
        if (!empty ($name)) {
            $name = \Illuminate\Support\Facades\Crypt::decrypt($name);
            userActiveModule::where('user_id', Auth::user()->id)->where('module', $name)->delete();

            return redirect()->back()->with('success', __('Successfully cancel subscription.'));
        } else {
            return redirect()->back()->with('error', __('Something went wrong please try again .'));
        }
    }

    public function add()
    {
        return view('module.add');
    }

    public function install(Request $request)
    {
        $zip = new ZipArchive;
        try {
            $res = $zip->open($request->file);
        } catch (\Exception $e) {
            return error_res($e->getMessage());
        }
        if ($res === TRUE) {
            $zip->extractTo('Modules/');
            $zip->close();
            $data['flag'] = 1;
            $data['msg'] = 'Install successfully.';
            return $data;
        } else {
            $data['flag'] = 2;
            $data['msg'] = 'oops something wren wrong';
            return $data;
        }
        $data['flag'] = 2;
        $data['msg'] = 'oops something wren wrong';
        return $data;

    }

    public function remove($module)
    {
        $module = Module::find($module);
        if ($module) {
            $module->disable();
            $module->delete();
            Artisan::call('module:migrate-refresh ' . $module);

            return redirect()->back()->with('success', __('Module delete successfully!'));
        } else {
            return redirect()->back()->with('error', __('oops something wren wrong!'));
        }

    }
}
