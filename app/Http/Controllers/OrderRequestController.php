<?php

namespace App\Http\Controllers;

use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Models\NFCCard;
use App\Models\Business;
use App\Models\Utility;
use App\Models\User;

class OrderRequestController extends Controller
{
    public function index(Request $request)
    {
        if (\Auth::user()->type == 'super admin')
        {
            $orderRequest = OrderRequest::get();
        }
        else
        {
            $orderRequest = OrderRequest::where('created_by',\Auth::user()->creatorId())->get();
        }
        

        foreach ($orderRequest as $key => $order) {
            $business_name = Business::where('id', $order->business_id)->pluck('title')->first();
            $nfcCardName = NFCCard::where('id', $order->nfc_card_id)->pluck('card_name')->first();
            $company_name = User::where('id', $order->user_id)->pluck('name')->first();
            $order->business_name = $business_name;
            $order->nfc_card_name = $nfcCardName;
            $order->company_name = $company_name;
        }

        return view('nfc.orderdetail', compact('orderRequest'));
    }
    public function nfcOrder($id)
    {
        $businessList = Business::where('created_by', \Auth::user()->creatorId())->pluck('title', 'id');
        $nfcCard = NFCCard::Find($id);
        return view('nfc.addtocart', compact('businessList', 'nfcCard'));
    }

    public function nfcOrderStore(Request $request, $id)
    {

        $orderID = time();
       

        $orderRequest = new OrderRequest();
        $orderRequest->order_id = $orderID;
        $orderRequest->nfc_card_id = $id;
        $orderRequest->business_id = $request->business;
        $orderRequest->quantity = $request->quantity;
        $orderRequest->price = $request->totalprice;
        $orderRequest->user_id = \Auth::user()->id;
        $orderRequest->created_by = \Auth::user()->creatorId();
        $orderRequest->save();
        return redirect()->back()->with('success', __('Order Created Successfully'));
    }
    public function OrderView($id)
    {
        $orderRequest = OrderRequest::find($id);

        $business_name = Business::where('id', $orderRequest->business_id)->pluck('title')->first();
        $nfcCardName = NFCCard::where('id', $orderRequest->nfc_card_id)->pluck('card_name')->first();
        $company_name = User::where('id', $orderRequest->user_id)->pluck('name')->first();
        $orderRequest->business_name = $business_name;
        $orderRequest->nfc_card_name = $nfcCardName;
        $orderRequest->company_name = $company_name;

        return view('nfc.orderview', compact('orderRequest'));
    }

    public function changeOrderStatus($id, $status)
    {

        $orderRequest = OrderRequest::find($id);
        $orderRequest->status = $status;
        $orderRequest->save();
        return redirect()->back()->with('success', __('Order Status successfully'));
    }

    public function acceptRequest($id, $response){
        $orderRequest = OrderRequest::find($id);
        if($response == 1)
        {
            $orderRequest->approval = $response;
            $orderRequest->save();
            return redirect()->back()->with('success', __('Order request accepted.'));
        }else
        {
            $orderRequest->approval = $response;
            $orderRequest->save();
            return redirect()->back()->with('error', __('Order status rejected.'));
        }
        
        
    }
}
