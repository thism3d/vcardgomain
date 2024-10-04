<?php

namespace App\Http\Controllers;

use App\Models\NFCCard;
use App\Models\Business;
use App\Models\Utility;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use File;

class NFCCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $nfcCardData = NFCCard::get();
        return view('nfc.index', compact('nfcCardData'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('nfc.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'nfc_card_name' => 'required|max:100',
                'price' => 'required',
                'nfc_image' => 'required',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        if ($request->hasFile('nfc_image')) {
            $settings = Utility::getStorageSetting();
            $logo = $request->file('nfc_image');
            $ext = $logo->getClientOriginalExtension();
            $fileName = 'nfc_' . time() . rand() . '.' . $ext;

            if ($settings['storage_setting'] == 'local') {
                $dir = 'nfc/card_image/';
            } else {
                $dir = 'nfc/card_image/';

            }
            $path = Utility::upload_file($request, 'nfc_image', $fileName, $dir, []);

            if ($path['flag'] == 1) {
                $url = $path['url'];
            } else {
                return redirect()->route('nfc.index', \Auth::user()->id)->with('error', __($path['msg']));
            }

        }


        $nfcCard = NFCCard::create([
            'card_name' => $request->nfc_card_name,
            'price' => $request->price,
            'image' => $fileName,
            'created_by' => \Auth::user()->creatorId()
        ]);
        $nfcCard->save();

        return redirect()->back()->with('success', __('NFC Card Created Successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(NFCCard $nFCCard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $nFCCardData = NFCCard::find($id);
        return view('nfc.edit', compact('nFCCardData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $nFCCardData = NFCCard::find($id);
        $validator = \Validator::make(
            $request->all(),
            [
                'nfc_card_name' => 'required|max:100',
                'price' => 'required',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        if ($request->hasFile('nfc_image')) {
            $settings = Utility::getStorageSetting();
            $logo = $request->file('nfc_image');
            $ext = $logo->getClientOriginalExtension();
            $fileName = 'nfc_' . time() . rand() . '.' . $ext;

            if ($settings['storage_setting'] == 'local') {
                $dir = 'nfc/card_image/';
            } else {
                $dir = 'nfc/card_image/';

            }
            $image_path = $dir . $nFCCardData->image;
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
            $path = Utility::upload_file($request, 'nfc_image', $fileName, $dir, []);

            if ($path['flag'] == 1) {
                $url = $path['url'];
            } else {
                return redirect()->route('nfc.index', \Auth::user()->id)->with('error', __($path['msg']));
            }
            $nFCCardData->image = $fileName;
        }
        $nFCCardData->card_name = $request->nfc_card_name;
        $nFCCardData->price = $request->price;
       
        $nFCCardData->created_by = \Auth::user()->creatorId();
        $nFCCardData->save();
        return redirect()->back()->with('success', __('NFC Card Updated Successfully'));


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $nFCCardData = NFCCard::Find($id);
        $orderRequest = OrderRequest::where('nfc_card_id',$id);
        $orderRequest->delete();
        $nFCCardData->delete();
        return redirect()->route('nfc.index')->with('success', __('NFC Card successfully deleted.')); 
        
    }

   
}
