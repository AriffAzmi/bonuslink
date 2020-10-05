<?php

namespace App\Http\Controllers;

use Auth,Cookie,Curl;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RedirectsUsers;

class SSOLoginController extends Controller
{
	use RedirectsUsers;

    public function auth(Request $request)
    {
    	// Determine if user is authenticated
    	if (!Auth::check()) {

    		// Check if have SSO cookie
    		if (Cookie::get('site_sso_id') !== null) {
    			
    			$sso_id = Cookie::get('site_sso_id');

    			// Check if sso_id valid
    			$check_sso_id = Curl::to(config('sso_url'))
    			->withData([
    				'sso_id' => $sso_id
    			])
    			->post();

    			$check_sso_id = json_decode($check_sso_id);

    			if ($check_sso_id->status === true) {

	    			// Authenticate the user
	    			if (Auth::guard()->attempt(['sso_id' => $sso_id])) {
	    				
	    				session()->regenerate();
	    				return redirect()->intended($this->redirectPath());
	    			}
	    			else {

	    				// Create the user account in the site and store the sso_id for the newly created user
	    				try {
	    					
	    					User::create([
	    						'sso_id' => $sso_id
	    					]);

	    					// Authenticate the user
	    					Auth::guard()->attempt(['sso_id' => $sso_id]);
	    					session()->regenerate();

	    					return redirect()->intended($this->redirectPath());

	    				} catch (\Exception $e) {
	    					
	    					return redirect()->route('login')->withErrors([
	    						'Failed to create user account for SSO login'
	    					]);
	    				}
	    			}
	    		}
	    		else {
	    			return redirect()->route('login')->withErrors([
						'SSO ID is not valid'
					]);
	    		}
    		}
    	}
    }
}
