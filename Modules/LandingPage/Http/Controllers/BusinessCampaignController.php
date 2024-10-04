<?php

namespace Modules\LandingPage\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LandingPage\Entities\LandingPageSetting;

class BusinessCampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $settings = LandingPageSetting::settings();
        $business_campaign = json_decode($settings['business_campaign'], true) ?? [];
        return view('landingpage::landingpage.business_campaign.index',compact('settings','business_campaign'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('landingpage::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
       
        if($request->business_campaign){
            $business_campaign = 'on';
            $validator = \Validator::make(
                $request->all(),
                [
                    'business_campaign_type' => 'required|in:latest,most_popular',
                ]
            );
            
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
    
                return redirect()->back()->with('error', $messages->first());
            }
        }else{
            $business_campaign = 'off';
        }
        $data['business_campaign']=$business_campaign;
        $data['business_campaign_title']= $request->business_campaign_title;
        $data['business_campaign_heading']= $request->business_campaign_heading;
        $data['business_campaign_description']= $request->business_campaign_description;
        $data['business_campaign_type']= $request->business_campaign_type;


        foreach($data as $key => $value){
            LandingPageSetting::updateOrCreate(['name' =>  $key],['value' => $value]);
        }

        return redirect()->back()->with(['success'=> 'Business Campaign update successfully']);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('landingpage::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('landingpage::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
