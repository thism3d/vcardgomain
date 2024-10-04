<?php

namespace Modules\LandingPage\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\LandingPageSection;
use App\Models\Plan;
use App\Models\Utility;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;
use App\Models\CardAppinfo;
use App\Models\CardPayment;
use App\Models\Product;
use App\Models\business_hours;
use App\Models\appoinment;
use App\Models\service;
use App\Models\social;
use App\Models\User;
use App\Models\ContactInfo;
use App\Models\testimonial;
use Illuminate\Support\Facades\Auth;
use App\Models\Gallery;
use App\Models\PixelFields;
use App\Models\Businessqr;
use Carbon\Carbon;
use App\Models\Campaigns;



class MarketplaceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        // Initialize query for businesses
        $businessQuery = Business::query();

        if ($request->orderby == 'popularity') {
            // Join the 'visitor' table to count visits using the slug
            $businessQuery->leftJoin('visitor', 'businesses.slug', '=', 'visitor.slug')
                ->select('businesses.*', DB::raw('COUNT(visitor.id) as visit_count'))
                ->groupBy('businesses.id')
                ->orderBy('visit_count', 'desc');
        } elseif ($request->orderby == 'latest') {
            $businessQuery->orderBy('created_at', 'desc');
        } else {
            $businessQuery->orderBy('created_at', 'asc');
        }

        $businessDetail = $businessQuery->get();
        $businessCounts = Business::select('business_category', DB::raw('COUNT(*) as count'))
            ->groupBy('business_category')
            ->get();
        $categoryData = BusinessCategory::get();
        $categoryData = $categoryData->map(function ($category) use ($businessCounts) {
            $category->count = $businessCounts->where('business_category', $category->id)->first()->count ?? 0;
            return $category;
        });

        $categoryList = BusinessCategory::get()->pluck('name', 'id');

        return view('landingpage::marketplace.index', compact('categoryData', 'businessDetail', 'categoryList'));
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
        //
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

    public function search(Request $request)
    {
        $query = $request->input('search-business');
        $selectedCategory = $request->input('category');

        // Create a query builder instance
        $businessQuery = Business::query();

        // Apply filters based on the search-business and category
        if ($query) {
            $businessQuery->where('title', 'like', "%$query%");
        }

        if ($selectedCategory) {
            $businessQuery->where('business_category', $selectedCategory);
        }

        $businessDetail = $businessQuery->get();

        // Count businesses in each category for displaying in the view
        $businessCounts = Business::select('business_category', DB::raw('COUNT(*) as count'))
            ->groupBy('business_category')
            ->get();

        // Fetch and map category data with counts
        $categoryData = BusinessCategory::get();
        $categoryData = $categoryData->map(function ($category) use ($businessCounts) {
            $category->count = $businessCounts->where('business_category', $category->id)->first()->count ?? 0;
            return $category;
        });
        $categoryList = BusinessCategory::get()->pluck('name', 'id');
        return view('landingpage::marketplace.index', compact('businessDetail', 'categoryData', 'selectedCategory', 'categoryList'));
    }
    public function landingHome(Request $request)
    {
        if (!file_exists(storage_path() . "/installed")) {
            header('location:install');
            die;
        } else {
            if (\Auth::check()) {
                return redirect('/home');
            } else {
                $settings = Utility::settings();
                if ($settings['display_landing_page'] == 'on' && \Schema::hasTable('landing_page_settings')) {
                    $plans = Plan::get();
                    $get_section = LandingPageSection::orderBy('section_order', 'ASC')->get();

                     $businessQuery = Business::join('campaigns', 'businesses.id', '=', 'campaigns.business')
                        ->where('campaigns.status',1)
                        ->select('businesses.*', 'campaigns.status as campaign_status', 'campaigns.name', 'campaigns.start_date', 'campaigns.end_date');

                    // Determine the sorting order, default to 'latest' if not specified
                    $orderby = $request->get('orderby', 'latest');
                    if ($orderby == 'popularity') {
                        // Join the 'visitor' table to count visits using the slug for popularity
                        $businessQuery->leftJoin('visitor', 'businesses.slug', '=', 'visitor.slug')
                            ->leftJoin('campaigns as c', 'businesses.id', '=', 'c.business') // Use an alias 'c' for campaigns
                            ->select(
                                'businesses.id', 
                                'businesses.slug', 
                                'businesses.title', 
                                'businesses.logo', 
                                'businesses.sub_title', 
                                'businesses.designation', 
                                'businesses.description',
                                'c.status as campaign_status', 
                                'c.name as campaign_name', 
                                'c.start_date as campaign_start_date', 
                                'c.end_date as campaign_end_date', 
                                DB::raw('COUNT(visitor.id) as visit_count')
                            )
                            ->where('c.status',1)
                            ->groupBy(
                                'businesses.id', 
                                'businesses.slug', 
                                'businesses.title', 
                                'businesses.logo', 
                                'businesses.sub_title', 
                                'businesses.designation', 
                                'businesses.description',
                                'c.status', 
                                'c.name', 
                                'c.start_date', 
                                'c.end_date'
                            ) // Group by all non-aggregated columns using alias
                            ->orderBy('visit_count', 'desc');
                    } elseif ($orderby == 'latest') {
                        $businessQuery->orderBy('businesses.created_at', 'desc');
                    } else {
                        $businessQuery->orderBy('businesses.created_at', 'asc');
                    }
                    // Filter based on campaign status
                    $businessDetail = $businessQuery->get();
                    return view('landingpage::layouts.landingpage', compact('plans', 'get_section', 'businessDetail'));
                } else {
                    return redirect()->route('login');
                }
            }
        }
    }
    public function share($slug)
    {

        $businessDetail = Business::where('slug', $slug)->first();
        return view('landingpage::marketplace.share_business', compact('businessDetail'));
    }
    public function cardView($slug)
    {
        $business = Business::where('slug', $slug)->first();
        return view('landingpage::marketplace.view_business', compact('business'));
    }
    public function contactData($id)
    {
        $contactDetail = ContactInfo::where('business_id', $id)->first();
        $contactinfo_content = [];
        if (!empty($contactDetail->content)) {
            $contactinfo_content = json_decode($contactDetail->content);
        }
        return view('landingpage::marketplace.contact_view', compact('contactDetail', 'contactinfo_content'));
    }

}
