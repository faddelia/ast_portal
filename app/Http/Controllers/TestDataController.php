<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\TestData;
use App\CompanyLookup;
use App\Company;

class TestDataController extends Controller
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

        if($request->has('data')) {
            $relationKey = "";
            
            $isValid = $this->validate($request, [
                'data'=>'required'
            ]);

            if($isValid) {

                //get customer company name
                $userCompanyId  = CompanyLookup::where('user_id', '=', Auth::user()->id)->first();
                $userCompanyName = Company::where('id', '=', $userCompanyId->company_id)->first();

                //for admin only, but also must be set with a company name for client!
                $viewing = '';

                if(Auth::user()->role == "admin") {
                    //get id of client viewing
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
                // $files = $request->file('file');
                $file = $request->file('data');

                $everythingGood = true;

                $originalFilename  = $file->getClientOriginalName();

                // append time() to filename to avoid collisions
                $filename     = substr($originalFilename, 0, strrpos($originalFilename, '.')) . '-' . time();
                $extension    = $file->getClientOriginalExtension();
                $check        = in_array($extension, $allowedfileExtension);
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

                if($everythingGood) {
                    //store user_id and document_name into product_documents
                    $testData                    = new TestData();
                    $testData->user_id           = Auth::user()->id;
                    $testData->document_name     = $fullFileName;
                    $testData->user_relation_key = $relationKey;
                    $saved = $testData->save();

                    if(Auth::user()->role == 'admin') {
                        return redirect('home/' . $request['viewing'])->with(['status', 'File Uploaded!']);
                    }

                    return redirect('home')->with('status', 'File uploaded!');
                }
                else {

                    if(Auth::user()->role == 'admin') {
                        return redirect('home/' . $request['viewing'])->with(['error', 'Something went wrong!!!']);
                    }

                    return redirect('home')->with(['error', 'Something went wrong!!!']);
                }
            }
            else {
                return redirect('home')->with(['error', 'File is not valid.']);
            }
        }
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //Using the testData's id and name to ensure that if the data is tampered with it won't delete the wrong file
        $testData = TestData::where('id', '=', $request->id)
            ->where('document_name', '=', $request->name)
            ->first();

        $userRelationkey = $testData->user_relation_key;
        $userRelationkey = explode(",", $userRelationkey);
        $userCompanyName = Company::where('id', '=', $userRelationkey[1])->first();

        if($testData !== null) {
            //delete record from database
            $testData->delete();

            //delete file from disk
            $filePath = 'public/files/' . strtolower(str_replace(" ", "_", $userCompanyName->name)) . '/' . $testData->document_name;
            Storage::delete($filePath);

            return response()->json(['success' => 'File deleted.']);
        }

        return response()->json(['error' => 'File not deleted.']);
    } // destroy()
}
