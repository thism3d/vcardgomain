<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;


class ModuleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $moduleName = null): Response
    {

        $redirectToRoute = null;
        if (
            !$request->user() ||
            ($request->user() instanceof MustVerifyEmail &&
                !$request->user()->hasVerifiedEmail())
        ) {
            return $request->expectsJson()
                ? abort(403, 'Your email address is not verified.')
                : Redirect::guest(URL::route($redirectToRoute ?: 'verification.notice'));
        }

        if (\Auth::user()->type != 'super admin') {
            if ($moduleName != null) {

                $moduleName = explode('-', $moduleName);
                $status = false;
                foreach ($moduleName as $m) {

                    $usermodule = $request->user()->active_module;
                    $usermoduleArray = explode(",", $usermodule);

                    foreach ($usermoduleArray as $module) {
                        // Trim any whitespace from the module name
                        $trimmedModule = trim($module);
                        // Check if the trimmed module name exists in $m array
                        if (in_array($trimmedModule, $moduleName)) {
                            $status = true; // Set status to true if any module matches
                            break; // Exit the loop early since we found a match
                        }
                    }
                }

                if ($status == true) {

                    //$active_module = \App\Models\Utility::ActivatedModule();
                    $active_module = $request->user()->active_module;
                    $usermoduleArray = explode(",", $active_module);
                    if (!empty(array_intersect($moduleName, $usermoduleArray))) {
                        $response = $next($request);
                        return $response;
                    }
                }
                return redirect()->route('home')->with('error', __('Permission denied'));
            } else {
                return redirect()->route('home')->with('error', __('Permission denied'));
            }
        }
        $response = $next($request);
        return $response;
    }
}
