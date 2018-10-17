<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\CompanyLookup;
use App\Company;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isAdmin = true;
        // Look up company name
        $companyLookup = CompanyLookup::where('user_id', '=', Auth::user()->id)->first();
        $companyName   = Company::where('id', '=', $companyLookup->company_id)->first(['name']);

        return view('forms.add-company', compact('isAdmin', 'companyName'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $company = new Company();
        $company->name = $request['name'];

        $company->save();

        session()->flash('success', 'Company ' . $company->name . ' added successfully!');

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company   $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Company   $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        $isAdmin    = true;
        $companies  = Company::all();

        // Look up company name
        $companyLookup = CompanyLookup::where('user_id', '=', Auth::user()->id)->first();
        $companyName   = Company::where('id', '=', $companyLookup->company_id)->first(['name']);

        // remove spaces and punctuation out of company name so it doesn't break front end
        foreach ($companies as $company) {
            $tmp = $company->name;
            $tmp = strtolower($tmp);
            $tmp = str_replace(" ", "_", $tmp);

            $company['fixed_name'] = $tmp;
        }

        return view('forms.manage-companies', compact('isAdmin', 'companies', 'companyName'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company   $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        //validate company name
        $isValid = $this->validate($request, [
            'company-id'   => 'required|integer',
            'company-name' => 'required|max:255',
        ]);

        if($request->has('update') && $isValid) {
            //lookup company by id
            $company = Company::find($request['company-id']);
            
            //update company name
            $company->name = $request['company-name'];

            //update record
            $company->save();

            session()->flash('success', 'Updated ' . $company->name . '!');
        }

        if($request->has('disable') && $isValid) {
            $company = Company::find($request['company-id']);
            $company->disable = 1;

            $company->save();
            session()->flash('success', 'Disabled ' . $company->name . '!');
        }

        if($request->has('enable') && $isValid) {
            $company = Company::find($request['company-id']);
            $company->disable = 0;

            $company->save();
            session()->flash('success', 'Enabled ' . $company->name . '!');
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company   $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        //
    }
}
