@extends('layouts.master')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
	<h1 id="company-name" class="h2"><a name="dashboard">Client Portal - AST</a></h1>
	<div class="btn-toolbar mb-2 mb-md-0">
		<div class="btn-group mr-2">
            <!--
            	<button class="btn btn-sm btn-outline-secondary">Share</button>
            	<button class="btn btn-sm btn-outline-secondary">Export</button>
            -->
        </div>
    </div>
</div>

<div id="content" class="container">

	<h4>Add Company</h4>

    @if(session('success'))
        @include('partials.success')
    @endif

    <form name="add-company" action="/add-company" method="post">
        @csrf
        <div class="row">
            <div class="col">
                <div class="form-group row">
                    <label for="companyName" class="col-sm-2 col-form-label">Company Name</label>
                    <div class="col-sm-10">
                        <input type="text" id="companyName" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <button type="submit" class="btn btn-primary">Add Company</button>
                </div>
            </div>
        </div>
    </form>

</div>

@endsection