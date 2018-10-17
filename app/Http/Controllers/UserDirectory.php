<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Company;
use App\CompanyLookup;

class UserDirectory extends Controller
{
    public function index()
    {

    	// Look up company name
        $companyLookup = CompanyLookup::where('user_id', '=', Auth::user()->id)->first();
        $companyName   = Company::where('id', '=', $companyLookup->company_id)->first(['name']);

        if($companyName->name !== 'AST' && $companyName->name !== 'ast') {
        	return redirect()->route('home');
        }


    	$isAdmin = (Auth::user()->role == 'admin') ? true : false;
        $companies = Company::all();
        
        return view('pages.user-directory', compact('isAdmin', 'companies', 'companyName'));
    }
}
