@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.request') }}" onsubmit="return validate();">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="newPassword" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="newPassword" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="confirmPassword" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="confirmPassword" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

$(document).ready(function(){
    $('#newPassword').popover({title: 'Password Requirements', content: "<ul class=\"list-group\"><li id=\"numChars\" class=\"list-group-item list-group-item-danger\">Must have 8-20 characters.</li><li id=\"digits\" class=\"list-group-item list-group-item-danger\">Must contain at least one digit 0-9</li><li id=\"specialChars\" class=\"list-group-item list-group-item-danger\">Must contain <span style=\"text-decoration: underline;\">one</span> of the following special characters: ! @ # $ % ^ & * ( )</li></ul>", html: true});

    $('#newPassword').focus(function(){
        $(this).popover('show');
    });

    $('#newPassword').blur(function(){
        $(this).popover('hide');
    });

    $('#newPassword').keyup(function(){
        validate();
    });

    $('#confirmPassword').popover({title: 'Confirm your password:', content: '<ul class=\"list-group\"><li id=\"matches\" class=\"list-group-item list-group-item-danger\">Your password does not match.</li></ul>', html: true});

    $('#confirmPassword').focus(function(){
        $(this).popover('show');
    });

    $('#confirmPassword').blur(function(){
        $(this).popover('hide');
    });

    $('#confirmPassword').keyup(function(){
        validate();
    });
});

function validate() {

    var validPassword = false;

    //make sure that the passwords match
    if ($('#newPassword').val() !== $('#confirmPassword').val()) {
        $('#matches').removeClass('list-group-item-success');
        $('#matches').addClass('list-group-item-danger');
        $('#matches').text('Your passwords do not match.');
    }
    else if ($('#newPassword').val() === '') {
        $('#matches').removeClass('list-group-item-success');
        $('#matches').addClass('list-group-item-danger');
        $('#matches').text('Your password is not valid because it is empty.');
    }
    else {
        $('#matches').removeClass('list-group-item-danger');
        $('#matches').addClass('list-group-item-success');
        $('#matches').text('Your passwords match.');
        validPassword = true;
    }

    if($('#newPassword').val().length < 8 || $('#newPassword').val().length > 20) {
        $('#numChars').removeClass('list-group-item-success');
        $('#numChars').addClass('list-group-item-danger');
    }
    else {
        $('#numChars').removeClass('list-group-item-danger');
        $('#numChars').addClass('list-group-item-success');
        validPassword = true;
    }

    //check for at least one punctuation character
    if (!/\W+/.test($('#newPassword').val())) {
        $('#specialChars').removeClass('list-group-item-success');
        $('#specialChars').addClass('list-group-item-danger');
    }
    else {
        $('#specialChars').removeClass('list-group-item-danger');
        $('#specialChars').addClass('list-group-item-success');
        validPassword = true;
    }

    //check for at least one digit
    if (!/[0-9]+/.test($('#newPassword').val())) {
        $('#digits').removeClass('list-group-item-success');
        $('#digits').addClass('list-group-item-danger');
    }
    else {
        $('#digits').removeClass('list-group-item-danger');
        $('#digits').addClass('list-group-item-success');
        validPassword = true;
    }

    if(!validPassword) {
        $('#newPassword').focus();
    }

    return validPassword;
}

</script>
@endsection
