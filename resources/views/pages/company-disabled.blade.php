@extends('layouts.master')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
	<h1 id="company-name" class="h2"><a name="dashboard">Client Portal - {{ $companyName[0]->name }}</a></h1>
</div>
	<div id="content" class="container">
		<div class="row">
			<div class="col">
				Oh no! It appears that {{ $companyName[0]->name }} has been disabled in our system. If this is an error, or {{ $companyName[0]->name }} would like to reinstate the account please contact <a href="mailto:sales@advancedspectral.com">sales@advancedspectral.com</a>.
			</div>
		</div>		
	</div>
@endsection