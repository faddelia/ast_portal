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

	<h4>Manage Companies</h4>

    @if(session('success'))
        @include('partials.success')
    @endif

    <div class="accordian" id="companies">
        @foreach( $companies as $company )
        <div class="card">
            <div class="card-header" id="company-{{ $company->fixed_name }}">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#{{ $company->fixed_name }}" aria-expanded="false" aria-controls="{{ $company->fixed_name }}">{{ $company->name }}</button>
                </h5>
            </div>

            <div id="{{ $company->fixed_name }}" class="collapse" aria-labelledby="company-{{ $company->fixed_name }}" data-parent="#companies">
                <div class="card-body">
                    <form method="post" action="/manage-companies">
                        @csrf
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label for="field-{{ $company->name }}" class="col-form-label">Company ID</label>
                                    <input type="text" class="form-control" name="company-id" id="field-{{ $company->id }}" value="{{ $company->id }}" readonly>
                                </div>                                
                                <div class="col-sm-9">
                                    <label for="field-{{ $company->name }}" class="col-form-label">Company Name</label>
                                    <input type="text" class="form-control" name="company-name" id="field-{{ $company->name }}" value="{{ $company->name }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10" style="margin-top: 10px;">
                                    <button type="submit" class="btn btn-primary" name="update">Update</button>&nbsp;&nbsp;
                                    <button type="submit" class="btn btn-danger" name="<?php echo ($company->disable == 0) ? 'dis' : 'en'; ?>able"><?php echo ($company->disable == 0) ? 'Dis' : 'En'; ?>able</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>

@endsection