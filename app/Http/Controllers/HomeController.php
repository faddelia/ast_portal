<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\ActionLog;
use App\Company;
use App\CompanyLookup;
use App\TestData;
use App\CurrentStatuses;
use App\ProductDocument;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* 
         * Need:
         * Client Company name (company lookup)
         * current_status
         * action_logs
         * product_documents 
         * test_data
         */

        if(Auth::check()) {

            $isAdmin = false;

            // Look up company name
            $companyLookup = CompanyLookup::where('user_id', '=', Auth::user()->id)->first();
            $companyName   = Company::where('id', '=', $companyLookup->company_id)->first();

            // get company id by name for $company
            $companyId = Company::where('name', '=', $companyName->name)->get(['id']);

            if(Auth::user()->last_password_change == null) {
                if(Auth::user()->role == 'admin') {
                    $isAdmin = true;
                }
                return redirect()->route('first-time-login');
                
            }

            if(Auth::user()->role == 'admin') {
                $isAdmin = true;
                $companies = Company::all();
                return view('pages.admin-dashboard', compact('isAdmin', 'companies', 'companyName'));
            }

            // If company disabled, give them disabled company messaged
            if ($companyName->disable) {
                return view('pages.company-disabled', compact('companyName', 'isAdmin'));
            }

            //get current statuses for logged in user
            $currentStatuses = CurrentStatuses::where('user_id', '=', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

            // If user disabled


            // If sortby not empty
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
            $actionLog = ActionLog::where('activity_key', '=', '1,' . $companyId[0]->id)
                ->where('archived', '=' , 0)
                ->orderBy($sortby, $sortdirection)
                ->get();

            //get product files for user
			$product_documents = ProductDocument::where('user_relation_key', '=', "1," . $companyId[0]->id)
                ->orderBy('created_at', 'desc')
                ->get();
			
            //get test data for user
            $test_data = TestData::where('user_relation_key', '=', "1," . $companyId[0]->id)
                ->orderBy('created_at', 'desc')
                ->get();

			$viewing = "";

			if(!$isAdmin) {
				$viewing = $companyName->name;
			}

            return view('pages.dashboard', compact('companyName', 'viewing', 'currentStatuses', 'actionLog', 'isAdmin', 'product_documents', 'test_data'));

        }

        return view('pages.dashboard');
    }

    public function showCompany($company)
    {
        // get company name of logged in user, should always be AST in this method, redirect them if not
        $companyLookup = CompanyLookup::where('user_id', '=', Auth::user()->id)->first();
        $companyName   = Company::where('id', '=', $companyLookup->company_id)->first();
		$companyViewing = Company::where('name', '=', $company)->first();

        if(Auth::user()->role == 'admin' || $companyName->name == 'AST') {
            // tell the view you're an admin
            $isAdmin = (Auth::user()->role == 'admin') ? true : false;

            // get company id by name for $company
            $companyId = Company::where('name', '=', $company)->first(['id']);

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

            //get company that admin is viewing
            $viewing = $companyViewing->name;

            //get current statuses for company (since admin can see all activity)
            //not sure if we need current statuses anymore... this may change to just reflect the most recent activity, like a snapshot. 
            //Discuss with Scott and Kelly
            $currentStatuses = CurrentStatuses::where('user_id', '=', "1," . Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

            //get action logs for action_key between admin and client
            $actionLog = ActionLog::where('activity_key', '=', '1,' . $companyId->id)
            ->where('archived', '=' , 0)
            ->where('deleted', '=' , 0)
            ->orderBy($sortby, $sortdirection)
            ->get();

            //get product files for user currently viewing
            $product_documents = ProductDocument::where('user_relation_key', '=', "1," . $companyId->id)
            ->where('deleted', '=', 0)
            ->orderBy('created_at', 'desc')
            ->get();

            //get test data for user
            $test_data = TestData::where('user_relation_key', '=', "1," . $companyId->id)
            ->orderBy('created_at', 'desc')
            ->get();

            return view('pages.dashboard', compact('companyName', 'currentStatuses', 'actionLog', 'isAdmin', 'viewing', 'product_documents', 'test_data'));
        }
        else {
            return redirect()->route('home');
        }
    }
}
