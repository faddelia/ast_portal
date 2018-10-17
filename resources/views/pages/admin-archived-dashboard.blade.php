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

	<h4>Company Directory</h4>

	<ul class="list-group">
	@foreach( $companies as $company )
		<li class="list-group-item"><a href="/archived/{{ $company->name }}">{{ $company->name }}</a></li>
	@endforeach
	</ul>
</div>

@endsection