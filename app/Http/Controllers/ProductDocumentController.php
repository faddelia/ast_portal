<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\ProductDocument;
use App\CompanyLookup;
use App\Company;

class ProductDocumentController extends Controller
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

        $type = "";
        $relationKey = "";
        
        $isValid = $this->validate($request, [
            'file'=>'required'
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
            $file = $request->file('file');

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
                $productDocument                    = new ProductDocument();
                $productDocument->user_id           = Auth::user()->id;
                $productDocument->document_name     = $fullFileName;
                $productDocument->document_type     = -1;
                $productDocument->user_relation_key = $relationKey;
                $saved = $productDocument->save();

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
        
    } // store()

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
        //Using the productDocument's id and name to ensure that if the data is tampered with it won't delete the wrong file
        $productDocument = ProductDocument::where('id', '=', $request->id)
            ->where('document_name', '=', $request->name)
            ->first();

        $userRelationkey = $productDocument->user_relation_key;
        $userRelationkey = explode(",", $userRelationkey);
        $userCompanyName = Company::where('id', '=', $userRelationkey[1])->first();

        if($productDocument !== null) {
            //delete record from database
            $productDocument->delete();

            //delete file from disk
            $filePath = 'public/files/' . strtolower(str_replace(" ", "_", $userCompanyName->name)) . '/' . $productDocument->document_name;
            Storage::delete($filePath);

            return response()->json(['success' => 'File deleted.']);
        }

        return response()->json(['error' => 'File not deleted.']);
    } // destroy()

    /**
     * Soft delete the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function softDelete(Request $request)
    {
        //Using the productDocument's id and name to ensure that if the data is tampered with it won't delete the wrong file
        $productDocument = ProductDocument::where('id', '=', $request->id)
            ->where('document_name', '=', $request->name)
            ->first();
        $productDocument->deleted = 1;
        $saved = $productDocument->save();

        if($productDocument !== null) {
            //destroy file
        }

        if($saved) {
            return response()->json(['success' => 'File deleted.', 'document' => $productDocument]);
        }
        else {
            return response()->json(['error' => 'File not deleted.']);
        }
    } // softDelete()

}
