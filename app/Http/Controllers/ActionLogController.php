<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\ActionLog;
use App\Company;
use App\CompanyLookup;

class ActionLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($url)
    {

        $companyName = explode("/", $url);

        if(Auth::check())
        {
            $company = Company::where('name', '=', strtolower($companyName[count($companyName) - 1]))->first();

            // If sortby not empty
            $sortby = "created_at";

            //assume desc (most recent)
            $sortdirection = 'desc';

            if(request()->has('sortdirection') && request()->sortdirection == 'asc')
            {
                $sortdirection = 'asc';
            }

            // if sortby is set
            if(request()->has('sortby')) 
            {
                $sortby = request()->sortby;

                switch($sortby) 
                {
                    case "date":
                        $sortby = "string_date";
                    break;
                    case "company":
                        $sortby = "company_name";
                    break;
                    case "name":
                        // do nothing
                    break;
                    case "communication-type":
                        $sortby = "communication_type";
                    break;
                    case "contact":
                        // do nothing
                    break;
                    case "subject":
                        $sortby = "status";
                    break;
                    case "assigned-to":
                        $sortby = "assigned_to";
                    break;
                    case "action":
                        $sortby = "action_item";
                    break;
                    case "assigned-to":
                        $sortby = "assigned_to";
                    break;
                    default:
                        $sortby = 'created_at';
                    break;
                }
            }
        }

        if($sortdirection == 'asc') {
            return Auth::user()->actionLogs
                ->where('activity_key', '=', '1,' . $company->id)
                ->sortBy($sortby);
        }
        
        return Auth::user()->actionLogs
            ->where('activity_key', '=', '1,' . $company->id)
            ->sortByDesc($sortby);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $action = new ActionLog();
        $action->string_date = $request->date;
        $action->user_id = Auth::user()->id;
        $action->company_name = $request->company;
        $action->name = $request->name;
        $action->communication_type = $request->communication_type;
        $action->contact = $request->contact;
        $action->status = $request->current_status;
        $action->action_item = $request->action_item;
        //need in the format <admin_company_id>,<client_company_id> for any store request from here, make sure AST's id is 1 :)
        $client_id = Company::where('name', '=', $request->action_key_client_name)->first(['id']);
        $action->activity_key = '1,' . $client_id->id; 

        if($request->assigned_to !== null) {
            $action->assigned_to = $request->assigned_to;
        }
        else {
            $action->assigned_to = "no one";   
        }

        $action->save();

        $actions = '';

        if(Auth::user()->role == 'admin') {
            $actions = ActionLog::where('user_id', '=', Auth::user()->id)
            ->where('activity_key', '=', '1,' . $client_id->id) //return only relevant activities between AST and client, even if the client is AST!
            ->orderBy('created_at', 'desc')
            ->get();
        }
        else {
            $actions = ActionLog::where('user_id', '=', Auth::user()->id)
            ->where('activity_key', '=', '1,' . $client_id->id)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'string_date', 'company_name', 'name', 'communication_type', 'contact', 'status', 'action_item']);
        }

        return response()->json(['success' => 'Data is successfully added', 'actions' => $actions, 'role' => Auth::user()->role]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    /**
     * Flag the specified resource as "deleted", but don't delete the record from the database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function softDelete(Request $request)
    {
        $actionLog = \App\ActionLog::where('id', '=', $request->id)->first();
        $actionLog->deleted = ($actionLog->deleted == 0) ? 1 : 0;
        $saved = $actionLog->save();

        if($saved) {
            return response()->json(['success' => 'Successfully deleted.']);
        }
        else {
            return response()->json(['error' => 'Did not delete.']);
        }
    }

    public function deleted()
    {
        //only admins can see this section!
        $isAdmin = (Auth::user()->role == 'admin') ? true : false;
        $companyLookup = \App\CompanyLookup::where('user_id', '=', Auth::user()->id)->first();
        $companyName   = Company::where('id', '=', $companyLookup->company_id)->first();

        // get company id by name for $company
        $companyId = Company::where('name', '=', $companyName->name)->first(['id']);

        $companies = Company::all();
        $viewing   = "all";
        
        return view('pages.admin-deleted-dashboard', compact('isAdmin', 'companies', 'viewing', 'companyName'));
    }

    public function showDeleted($company)
    {
        // tell the view whether or not user is an admin
        $isAdmin = (Auth::user()->role == 'admin') ? true : false;

        $companyLookup = \App\CompanyLookup::where('user_id', '=', Auth::user()->id)->first();
        $companyName   = Company::where('id', '=', $companyLookup->company_id)->first();

        // get company id by name for $company
        $companyId = Company::where('name', '=', $company)->first(['id']);

        $sortby = "created_at";
        //assume desc
        $sortdirection = 'desc';

        $viewing = $company;

        if(request()->has('sortdirection') && request()->sortdirection == 'asc')
        {
            $sortdirection = 'asc';
        }

            // if sortby is set
        if(request()->has('sortby')) 
        {
            $sortby = request()->sortby;

            switch($sortby) 
            {
                case "date":
                    $sortby = "string_date";
                break;
                case "company":
                    $sortby = "company_name";
                break;
                case "name":
                    // do nothing
                break;
                case "communication-type":
                    $sortby = "communication_type";
                break;
                case "contact":
                    // do nothing
                break;
                case "subject":
                    $sortby = "status";
                break;
                case "assigned-to":
                    $sortby = "assigned_to";
                break;
                case "action":
                    $sortby = "action_item";
                break;
                case "assigned-to":
                    $sortby = "assigned_to";
                break;
                default:
                    $sortby = 'created_at';
                break;
            }
        }

        //get action logs for user
        $actionLog = \App\ActionLog::where('activity_key', '=', '1,' . $companyId->id)
            ->where('deleted', '=', 1)
            ->orderBy($sortby, $sortdirection)
            ->get();

        return view('pages.deleted', compact('companyName', 'actionLog', 'isAdmin', 'viewing'));
    }

    public function archive(Request $request)
    {
        $actionLog = \App\ActionLog::where('id', '=', $request->id)->first();
        $actionLog->archived = ($actionLog->archived == 0) ? 1 : 0;
        $saved = $actionLog->save();

        if($saved) {
            return response()->json(['success' => 'Data is successfully added']);
        }
        else {
            return response()->json(['error' => 'Did not save']);
        }
    }

    public function archived()
    {
        if(Auth::check()) {
            //dd(Auth::user()->id);

            // tell the view whether or not user is an admin
            $isAdmin = (Auth::user()->role == 'admin') ? true : false;

            $companyLookup = \App\CompanyLookup::where('user_id', '=', Auth::user()->id)->first();
            $companyName   = Company::where('id', '=', $companyLookup->company_id)->first();

            // get company id by name for $company
            $companyId = Company::where('name', '=', $companyName->name)->first(['id']);

            if($isAdmin) {
                $companies = Company::all();
                $viewing   = "all";
                return view('pages.admin-archived-dashboard', compact('isAdmin', 'companies', 'viewing', 'companyName'));
            }

            $sortby = "created_at";
            //assume desc
            $sortdirection = 'desc';

            if(request()->has('sortdirection') && request()->sortdirection == 'asc')
            {
                $sortdirection = 'asc';
            }

            // if sortby is set
            if(request()->has('sortby')) 
            {
                $sortby = request()->sortby;

                switch($sortby) 
                {
                    case "date":
                        $sortby = "string_date";
                    break;
                    case "company":
                        $sortby = "company_name";
                    break;
                    case "name":
                        // do nothing
                    break;
                    case "communication-type":
                        $sortby = "communication_type";
                    break;
                    case "contact":
                        // do nothing
                    break;
                    case "subject":
                        $sortby = "status";
                    break;
                    case "assigned-to":
                        $sortby = "assigned_to";
                    break;
                    case "action":
                        $sortby = "action_item";
                    break;
                    case "assigned-to":
                        $sortby = "assigned_to";
                    break;
                    default:
                        $sortby = 'created_at';
                    break;
                }
            }

            //get action logs for user
            $actionLog = \App\ActionLog::where('activity_key', '=', '1,' . $companyId->id)
            ->where('archived', '=', 1)
            ->orderBy($sortby, $sortdirection)
            ->get();

            return view('pages.archived', compact('companyName', 'actionLog', 'isAdmin'));
        }
        else {
            return redirect()->route('home');
        }
    }

    public function showArchive($company)
    {
        //dd($company);

        // tell the view whether or not user is an admin
        $isAdmin = (Auth::user()->role == 'admin') ? true : false;

        $companyLookup = \App\CompanyLookup::where('user_id', '=', Auth::user()->id)->first();
        $companyName   = Company::where('id', '=', $companyLookup->company_id)->first();

        // get company id by name for $company
        $companyId = Company::where('name', '=', $company)->first(['id']);

        $sortby = "created_at";
        //assume desc
        $sortdirection = 'desc';

        $viewing = $company;

        if(request()->has('sortdirection') && request()->sortdirection == 'asc')
        {
            $sortdirection = 'asc';
        }

            // if sortby is set
        if(request()->has('sortby')) 
        {
            $sortby = request()->sortby;

            switch($sortby) 
            {
                case "date":
                    $sortby = "string_date";
                break;
                case "company":
                    $sortby = "company_name";
                break;
                case "name":
                    // do nothing
                break;
                case "communication-type":
                    $sortby = "communication_type";
                break;
                case "contact":
                    // do nothing
                break;
                case "subject":
                    $sortby = "status";
                break;
                case "assigned-to":
                    $sortby = "assigned_to";
                break;
                case "action":
                    $sortby = "action_item";
                break;
                case "assigned-to":
                    $sortby = "assigned_to";
                break;
                default:
                    $sortby = 'created_at';
                break;
            }
        }

        //get action logs for user
        $actionLog = \App\ActionLog::where('activity_key', '=', '1,' . $companyId->id)
            ->where('archived', '=', 1)
            ->orderBy($sortby, $sortdirection)
            ->get();

        return view('pages.archived', compact('companyName', 'actionLog', 'isAdmin', 'viewing'));
    }
}
