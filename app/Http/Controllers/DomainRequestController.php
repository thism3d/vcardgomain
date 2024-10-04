<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DomainRequest;

class DomainRequestController extends Controller
{
    public function index()
    {
        if (\Auth::user()->type == 'super admin') {
            $domain_request = DomainRequest::with('user')->with('business')->get();
            return view('domain_request.index', compact('domain_request'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function destroy($id)
    {
        if (\Auth::user()->type == 'super admin') {
            $domain_requests = DomainRequest::find($id);
            $domain_requests->delete();

            return redirect()->route('domain_request.index')->with('success', __('Request deleted successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function updateRequestStatus($id, $response)
    {
        if(\Auth::user()->type == 'super admin')
        {
            $domain_requests = DomainRequest::find($id);

            if(!empty($domain_requests))
            {
                if($response == 1)
                {
                    $domain_requests->status = 1;
                    $domain_requests->update();
                } else {
                    $domain_requests->status = '2';
                    $domain_requests->update();

                    return redirect()->back()->with('success', __('Request Rejected Successfully.'));
                }
                return redirect()->back()->with('success', __('Request Approved Successfully.'));
            } else {
                return redirect()->back()->with('error', __('Something went wrong.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
