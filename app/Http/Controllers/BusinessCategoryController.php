<?php

namespace App\Http\Controllers;

use App\Models\BusinessCategory;
use App\Models\Utility;
use Illuminate\Http\Request;
use File;

class BusinessCategoryController extends Controller
{
    public function index()
    {
        if (\Auth::user()->type = 'super admin') {
            $categoryData = BusinessCategory::get();
            return view('business_category.index', compact('categoryData'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }


    }

    public function create()
    {
        return view('business_category.create');
    }
    public function store(Request $request)
    {

        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'category_image' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        if ($request->hasFile('category_image')) {
            $settings = Utility::getStorageSetting();
            $logo = $request->file('category_image');
            $ext = $logo->getClientOriginalExtension();
            $fileName = 'cat_' . time() . rand() . '.' . $ext;

            if ($settings['storage_setting'] == 'local') {
                $dir = 'category/';
            } else {
                $dir = 'category/';

            }
            $path = Utility::upload_file($request, 'category_image', $fileName, $dir, []);

            if ($path['flag'] == 1) {
                $url = $path['url'];
            } else {
                return redirect()->route('category.index', \Auth::user()->id)->with('error', __($path['msg']));
            }

        }

        $categoryData = new BusinessCategory();
        $categoryData->name = $request->name;
        $categoryData->logo = $fileName;
        $categoryData->created_by = \Auth::user()->creatorId();
        $categoryData->save();


        return redirect()->back()->with('success', 'Business Category successfully created.');
    }
    public function edit($id)
    {
        $categoryData = BusinessCategory::find($id);
        return view('business_category.edit', compact('categoryData'));
    }
    public function update(Request $request, $id)
    {
        $categoryData = BusinessCategory::find($id);

        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        if ($request->hasFile('category_image')) {
            $settings = Utility::getStorageSetting();
            $logo = $request->file('category_image');
            $ext = $logo->getClientOriginalExtension();
            $fileName = 'cat_' . time() . rand() . '.' . $ext;

            if ($settings['storage_setting'] == 'local') {
                $dir = 'category/';
            } else {
                $dir = 'category/';

            }
            $image_path = $dir . $categoryData->logo;
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
            $path = Utility::upload_file($request, 'category_image', $fileName, $dir, []);

            if ($path['flag'] == 1) {
                $url = $path['url'];
            } else {
                return redirect()->route('category.index', \Auth::user()->id)->with('error', __($path['msg']));
            }
            $categoryData->logo = $fileName;

        }
        $categoryData->name = $request->name;

        $categoryData->created_by = \Auth::user()->creatorId();
        $categoryData->save();
        return redirect()->back()->with('success', __('Business Category Updated Successfully'));
    }

    public function destroy($id)
    {

        $category = BusinessCategory::find($id);
        if ($category) {
            // User::where('created_by', $id)->delete();
            $category->delete();

            return redirect()->route('category.index')->with('success', __('Business Category Successfully Deleted .'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong.'));
        }

    }

}
