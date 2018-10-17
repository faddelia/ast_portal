<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\CompanyLookup;
use App\Company;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isAdmin = (Auth::user()->role == 'admin') ? true: false;
        $companies = Company::all();

        // Look up company name
        $companyLookup = CompanyLookup::where('user_id', '=', Auth::user()->id)->first();
        $companyName   = Company::where('id', '=', $companyLookup->company_id)->first(['name']);
        
        return view('forms.add-user', compact('isAdmin', 'companies', 'companyName'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $valid = "";


        //try {
            $valid = $this->validate($request, [
                'first_name' => 'required|max:255',
                'last_name'  => 'required|max:255',
                'phone'      => 'max:255',
                'email'      => 'required|email|max:255',
                'password'   => 'required|max:255',
            ]);
        //}
        //catch(QueryException $q) {
        //    $valid = false;
        //}

        if($valid) {
            User::create([
                'name'     => $request['first_name'] . ' ' . $request['last_name'],
                'company'  => $request['company'],
                'phone'    => $request['phone'],
                'email'    => $request['email'],
                'password' => Hash::make($request['password']),
                'role'     => $request['user-role']
            ]);

            //get user id and company id of user just added
            $user_id = \App\User::where('email', '=', $request['email'])->get(['id']);
            $company_id = \App\Company::where('name', '=', $request['company'])->get(['id']);

            //add id and company they are assigned to to company lookup

            $comp_lookup             = new CompanyLookup();
            $comp_lookup->user_id    = $user_id[0]->id;
            $comp_lookup->company_id = $company_id[0]->id;

            $comp_lookup->save();

            session()->flash('success', 'User ' . $request['first_name'] . ' ' . $request['last_name'] . ' added successfully!');
        }
        else {
            //not valid
            session()->flash('error', 'User exists.');
        }

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $isAdmin = true;
        $users = User::all();
        $companies = Company::all();

        // Look up company name
        $companyLookup = CompanyLookup::where('user_id', '=', Auth::user()->id)->first();
        $companyName   = Company::where('id', '=', $companyLookup->company_id)->first(['name']);

        $counter = 0;
        foreach ($users as $user) 
        {
            $lookup = CompanyLookup::where('user_id', '=', $user->id)->get(['company_id']);
            $user_company = Company::where('id', '=', $lookup[0]->company_id)->get(['name']);
            $user['company'] = $user_company[0]->name;
        }
        return view('forms.manage-users', compact('isAdmin', 'users', 'companies', 'companyName'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //dd($request['name']);
        $name = strtolower(str_replace(' ', '-', $request['name']));

        if($request->has('update-' . $name)) {
            $valid = $this->validate($request, [
                'name'    => 'required|max:255',
                'phone'   => 'max:255',
                'email'   => 'required|email|max:255',
                'company' => 'required|max:255',
            ]);

            if($valid) {
                $userToUpdate = \App\User::where('name', '=', $request['name'])->first(['id', 'name', 'email', 'role', 'phone']);
                $lookup = \App\CompanyLookup::where('user_id', '=', $userToUpdate['id'])->get(['company_id']);
                $companyName = \App\Company::where('id', '=', $lookup[0]->company_id)->get(['name']);
                $store = false;
                //dd($request['role']);

                if($userToUpdate['name'] != $request['name']) {
                    $userToUpdate['name'] = $request['name'];
                    $store = true;
                }

                if($userToUpdate['email'] != $request['email']) {
                    $userToUpdate['email'] = $request['email'];
                    $store = true;
                }
                
                if($userToUpdate['phone'] != $request['phone']) {
                    $userToUpdate['phone'] = $request['phone'];
                    $store = true;
                }
                

                if($userToUpdate['role'] != $request['role']) {
                    $userToUpdate['role'] = $request['role'];
                    $store = true;
                }

                /*
                if($request['password'] != '') {
                    //check if password and password-confirm match
                    $store = true;
                }
                */

                if($companyName != $request['company']) {
                    $companyId = \App\Company::where('name', '=', $request['company'])->first(['id']);
                    if(\App\CompanyLookup::where('user_id', '=', $userToUpdate['id'])->exists()) {
                        //update record
                        $lookupUpdate               = \App\CompanyLookup::where('user_id', '=', $userToUpdate['id'])->first();
                        $lookupUpdate['company_id'] = $companyId['id'];
                        $lookupUpdate->save();
                    }
                    else {
                        //create record
                        $lookupUpdate             = new CompanyLookup();
                        $lookupUpdate->user_id    = $userToUpdate['id'];
                        $lookupUpdate->company_id = $companyId['id'];
                        $lookupUpdate->save();
                    }
                }

                if($store) {
                    $userToUpdate->save();
                    session()->flash('success', $request['name'] . ' updated!');
                }
                
            }
            else {
                session()->flash('error', 'Date is not valid :(');
            }
        }

        if($request->has('disable-' . $name)) {
            //dd($request['email']);
            $userToUpdate = User::where('email', '=', $request['email'])->first();
            $userToUpdate->disable = 1;
            if($userToUpdate->save()) {
                session()->flash('success', $request['name'] . ' disabled!');
            }
            else {
                session()->flash('error', 'Data not valid.');
            }
        }
        
        if($request->has('enable-' . $name)) {
            //dd($request['email']);
            $userToUpdate = User::where('email', '=', $request['email'])->first();
            $userToUpdate->disable = 0;
            if($userToUpdate->save()) {
                session()->flash('success', $request['name'] . ' enabled!');
            }
            else {
                session()->flash('error', 'Data not valid.');
            }
        }


        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
