<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Words;

class FirstTimeLogin extends Controller
{
    public function index()
    {
    	$isAdmin   = (Auth::user()->role == 'admin') ? true : false;
    	$words     = Words::orderByRaw('RAND()')->take(500)->get();
    	$lists     = array();
    	$listIndex = 0;
    	$offset    = 0;

        // Look up company name
        $companyLookup = \App\CompanyLookup::where('user_id', '=', Auth::user()->id)->first();
        $companyName   = \App\Company::where('id', '=', $companyLookup->company_id)->first();

        // get company id by name for $company
        $companyId = \App\Company::where('name', '=', $companyName->name)->get(['id']);

    	//$test = explode("\r\n", $words[0]->word);
    	foreach ($words as $word) {
    		if($offset / 100 == 1) {
    			$offset = 0;
    			$listIndex++;
    		}

    		$lists[$listIndex][] = explode("\r\n", $word->word)[0];

    		$offset++;
    	}

    	return view('forms.first-time-login', compact('isAdmin', 'lists', 'companyName'));
    }

    public function store(Request $request)
    {

    	$valid = $this->validate($request, [
                'newpassword' => 'required|max:100',
                'confirmpassword'  => 'required|max:100',
            ]);

    	/*
    	if (!preg_match("/^[A-Za-z]/", $request['newpassword']) || !preg_match("/[0-9]+/", $request['newpassword']) || !preg_match("/(!|@|#|\\$|%|\\^|&|\\*|\\(|\\))+/", $$request['newpassword'])) {
			$valid = false;
		}*/

    	//'password' => Hash::make($request['password']),

    	//dd(Auth::user()->email);
    	if($valid && strlen($request['newpassword']) > 0 && $request['newpassword'] == $request['confirmpassword']) {
	    	$user = User::where('email', '=', Auth::user()->email)->first();
	    	$user->password = Hash::make($request['newpassword']);
	    	$user->last_password_change = date('Y-m-d');
	    	$user->save();

	    	return redirect()->route('home');
    	}

    	if(!$valid) {
    		session()->flash('error', 'Password is not valid. Passwords must be between 8-20 characters, contain at least one digit, and contain at least one special symbol: ! @ # $ % ^ & * ( )');
    	}

    	if(strlen($request['newpassword']) == 0) {
    		session()->flash('error', 'Your password is empty. Please enter a valid password.');
    	}

    	if($request['newpassword'] !== $request['confirmpassword']) {
    		session()->flash('error', 'Your passwords do not match. Please make sure that you confirm the your new password correctly.');
    	}

    	return back();
    }
}
