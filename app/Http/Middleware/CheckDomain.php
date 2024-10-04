<?php

namespace App\Http\Middleware;

use App\Models\DomainRequest;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(file_exists(storage_path() . "/installed")){
            $uri = url()->full();
            $segments = explode('/', str_replace(''.url('').'', '', $uri));
            $segments = $segments[1] ?? null;
            if($segments == null) {
                $local = parse_url(config('app.url'))['host'];
                // Get the request host
                $remote = request()->getHost();
                // Get the remote domain
                
                // remove WWW
                
                $remote = str_replace('www.', '', $remote);
                
                if ($local != $remote){
                    
                    $domain = DomainRequest::where('status','1')->where('domain_name',$remote)->first();
                    // If the domain exists
                    if(isset($domain) && !empty($domain)) {
                        $business = \App\Models\Business::find($domain->store_id);
                        if($business && $business->enable_domain == 'on') {
                            return $next($request);
                        }
                    } else {
                        $sub_business = \App\Models\Business::where('subdomain', '=', $remote)->where('enable_subdomain', 'on')->first();
                        if ($sub_business && $sub_business->enable_subdomain == 'on') {
                            return $next($request);
                        } else {
                            return abort('404', 'Not Found');
                        }
                    }
                }
            }
            return $next($request);
        }else{
            return abort('404', 'Not Found');
        }
    }
}
