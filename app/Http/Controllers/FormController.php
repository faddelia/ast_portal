<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\ProductDocument;
use App\User;
use App\CompanyLookup;
use App\Company;

class FormController extends Controller
{

    public function fileUpload(Request $request)
    {

        $type = "";
        $relationKey = "";

        if($request->has('product')) {
            $type = "product";
            $this->validate($request, [
                'file'=>'required',
            ]);
        }

        if($request->has('data')) {
            $type = "data";
            $this->validate($request, [
                'data'=>'required',
            ]);
        }

        if($request->hasFile('file') || $request->hasFile('data'))
        {

            //get customer company name
            $userCompanyId  = CompanyLookup::where('user_id', '=', Auth::user()->id)->first();
            $userCompanyName = Company::where('id', '=', $userCompanyId->company_id)->first();

            //for admin only, but also must be set with a company name for client!
            $viewing = '';

            //dd(Auth::user()->id . " " . Auth::user()->name . " " . Auth::user()->role);
            if(Auth::user()->role == "admin") {
                //get id of client viewing
                //$viewing = Company::where('name', '=', $request['viewing'])->first(['id'])->name;
                $viewingId = Company::where('name', '=', $request['viewing'])->first(['id'])->id;
                $viewing   = Company::where('name', '=', $request['viewing'])->first(['name'])->name;
                
                //set up string for user_relation_key
                $relationKey = "1," . $viewingId;
            }
            else {
                // user is client
                $relationKey = "1," . $userCompanyId->company_id;
				
				// viewing must be set with company name!
				$viewing = $userCompanyName->name;
            }

            $allowedfileExtension=['pdf','jpg','png','docx', 'doc', 'csv', 'xlsx', 'xls', 'tiff', 'tif'];
            //$files = $request->file('file');
            $file = 'not set';

            if($request->hasFile('file')) {
                $file = $request->file('file');
            }

            if($request->hasFile('data')) {
                $file = $request->file('data');
            }

            //$paths = [];
            $everythingGood = true;

            //foreach($files as $file){

                $originalFilename  = $file->getClientOriginalName();
                $filename = substr($originalFilename, 0, strrpos($originalFilename, '.')) . '-' . time();
                //$filename = substr($originalFilename, 0, strrpos($originalFilename, '.'));
                $extension = $file->getClientOriginalExtension();
                $check     = in_array($extension,$allowedfileExtension);
                $fullFileName = $filename . '.' . $extension;

                if($check)
                {
                    //$paths[] = $file->storeAs('public/files/' . strtolower(str_replace(" ", "_", $company[0]->name)), $fullFileName);
                    //$paths = $file->storeAs('public/files/' . strtolower(str_replace(" ", "_", $request['viewing'])), $fullFileName);
					$paths = $file->storeAs('public/files/' . strtolower(str_replace(" ", "_", $viewing)), $fullFileName);
                }
                else {
                    $everythingGood = false;
                }
            //}

            if($everythingGood) {
                //store user_id and document_name into product_documents
                $productDocument = new ProductDocument();
                $productDocument->user_id = Auth::user()->id;
                $productDocument->document_name = $fullFileName;
                $productDocument->document_type = $type;
                $productDocument->user_relation_key = $relationKey;
                $productDocument->save();

                if(Auth::user()->role == 'admin') {
                    return redirect('home/' . $request['viewing'])->with(['status', 'File Uploaded!']);
                }
                return redirect('home')->with('status', 'File uploaded!');
            }
            else {
                //return response()->json(['error' => 'Something went wrong!!!', 'request' => $request]);
                if(Auth::user()->role == 'admin') {
                    return redirect('home/' . $request['viewing'])->with(['error', 'Something went wrong!!!']);
                }
                return redirect('home')->with(['error', 'Something went wrong!!!']);
            }
        }
        else {
            //return response()->json(['error' => 'Does not have file.', 'request' => $request]);
            if(Auth::user()->role == 'admin') {
                return redirect('home/' . $request['viewing'])->with(['error', 'Does not have file!!!']);
            }
            return redirect('home')->with(['error', 'Does not have file!!!']);
        }
    } //fileUpload

    public function assignTo(Request $request)
    {
        //dd($request->assigned_to);
        $id = explode("-", $request->lookup_id);
        $id = $id[1];

        $record = \App\ActionLog::where('id', '=', $id)
                ->orderBy('created_at', 'desc')
                ->first();

        $record->assigned_to = $request->assigned_to;
        $record->save();

        return response()->json(['success' => 'Sent data successfully', 'request' => $request->assigned_to, 'lookup_id' => $request->lookup_id]);
    } //assignTo
}
