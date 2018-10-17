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

	<h4>Manage Users</h4>
    
    @if(session('success'))
        @include('partials.success')
    @endif

    <div class="accordion" id="manage-users">
        @foreach ($users as $user)
        <div class="card">
            <div class="card-header" id="user-{{ $user->id }}">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#{{ $user->id }}" aria-expanded="false" aria-controls="{{ $user->id }}">{{ $user->name }}</button>
                </h5>
            </div>
            <div id="{{ $user->id }}" class="collapse" aria-labelledby="user-{{ $user->id }}" data-parent="#manage-users">
                <div class="card-body">
                    <form method="post" action="/manage-users">
                        @csrf
                        <label for="name-<?php echo strtolower(str_replace(' ', '-', $user->name)); ?>" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name-<?php echo strtolower(str_replace(' ', '-', $user->name)); ?>" name="name" value="{{ $user->name }}" required>
                        </div>
                        <label for="email-<?php echo strtolower(str_replace(' ', '-', $user->name)); ?>" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="email-<?php echo strtolower(str_replace(' ', '-', $user->name)); ?>" name="email" value="{{ $user->email }}" required>
                        </div>
                        <label for="company-<?php echo strtolower(str_replace(' ', '-', $user->name)); ?>" class="col-sm-2 col-form-label">Company</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="company-<?php echo strtolower(str_replace(' ', '-', $user->name)); ?>" name="company" autocomplete="off">
                                <option value="no company">Select Company</option>
                                @foreach ($companies as $company)
                                <option value="{{ $company->name }}"<?php echo ($company->name === $user->company) ? ' selected' : ''; ?>>{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label for="phone-<?php echo strtolower(str_replace(' ', '-', $user->name)); ?>" class="col-sm-2 col-form-label">Phone</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="phone-<?php echo strtolower(str_replace(' ', '-', $user->name)); ?>" name="phone" value="{{ $user->phone }}">
                        </div>
                        <label for="password-<?php echo strtolower(str_replace(' ', '-', $user->name)); ?>" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="password-<?php echo strtolower(str_replace(' ', '-', $user->name)); ?>" name="password">
                        </div>
                        <label for="password-confirm-<?php echo strtolower(str_replace(' ', '-', $user->name)); ?>" class="col-sm-2 col-form-label">Confirm Password</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="password-confirm-<?php echo strtolower(str_replace(' ', '-', $user->name)); ?>" name="password-confirm">
                        </div>
                        <div class="col-sm-10">
                            <div class="custom-control custom-radio">
                                <input type="radio" id="client-<?php echo strtolower(str_replace(' ', '-', $user->name)); ?>" name="role" class="custom-control-input" value="client"<?php echo ($user->role === 'client') ? ' checked' : ''; ?>>
                                <label class="custom-control-label" for="client-<?php echo strtolower(str_replace(' ', '-', $user->name)); ?>">Client</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="admin-<?php echo strtolower(str_replace(' ', '-', $user->name)); ?>" name="role" class="custom-control-input" value="admin"<?php echo ($user->role === 'admin') ? ' checked' : ''; ?>>
                                <label class="custom-control-label" for="admin-<?php echo strtolower(str_replace(' ', '-', $user->name)); ?>">Admin</label>
                            </div>
                        </div>
                        <div class="col-sm-10" style="margin-top: 10px;">
                            <button class="btn btn-primary" name="update-<?php echo strtolower(str_replace(' ', '-', $user->name)); ?>" type="submit">Update</button>
                            &nbsp;&nbsp;
                            <button class="btn btn-danger" name="<?php echo ($user->disable == 0) ? 'dis' : 'en'; ?>able-<?php echo strtolower(str_replace(' ', '-', $user->name)); ?>" type="submit"><?php echo ($user->disable == 0) ? 'Dis' : 'En'; ?>able</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
      @endforeach
    </div>

</div>

@endsection