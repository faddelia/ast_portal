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

	<h4>Add User</h4>

	<form action="/add-user" method="post">
		@csrf
		<div class="form-group row">
			<label for="first_name" class="col-sm-2 col-form-label">First Name</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name">
			</div>
		</div>

		<div class="form-group row">
			<label for="last_name" class="col-sm-2 col-form-label">Last Name</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name">
			</div>
		</div>

		<div class="form-group row">
			<label for="company" class="col-sm-2 col-form-label">Company</label>
			<div class="col-sm-10">
				<select id="company" name="company" class="form-control">
					<option>Select Company</option>
					@foreach ($companies as $company)
					<option value="{{ $company->name }}">{{ $company->name }}</option>
					@endforeach
				</select>
			</div>
		</div>

		<div class="form-group row">
			<label for="phone" class="col-sm-2 col-form-label">Phone</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
			</div>
		</div>

		<div class="form-group row">
			<label for="email" class="col-sm-2 col-form-label">Email</label>
			<div class="col-sm-10">
				<input type="email" class="form-control" id="email" name="email" placeholder="Email">
			</div>
		</div>

		<div class="form-group row">
			<label for="password" class="col-sm-2 col-form-label">Password</label>
			<div class="col-sm-7">
				<input type="text" class="form-control" id="password" name="password" placeholder="Password">
			</div>
			<div class="col-sm-3" style="text-align: right;">
				<button type="button" id="generate-password" class="btn btn-secondary">Generate Easy Password</button>
			</div>
		</div>

		<fieldset class="form-group">
			<div class="row">
				<legend class="col-form-label col-sm-2 pt-0">User Role</legend>
				<div class="col-sm-10">
					<div class="form-check">
						<input class="form-check-input" type="radio" name="user-role" id="client" value="client" checked>
						<label class="form-check-label" for="client">
							Client
						</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="radio" name="user-role" id="admin" value="admin">
						<label class="form-check-label" for="admin">
							Admin
						</label>
					</div>
				</div>
			</div>
		</fieldset>
		<div class="form-group row">
			<div class="col-sm-10">
				<button type="submit" class="btn btn-primary">Create User</button>
				<button type="reset" class="btn btn-danger">Reset Form</button>
			</div>
		</div>
	</form>
	
</div>

@endsection