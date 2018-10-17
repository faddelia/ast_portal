@extends('layouts.master')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
	<h1 id="company-name" class="h2"><a name="dashboard">Client Portal</a></h1>
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

	<h4>Update Password</h4>
    <br>

    <div class="row">
        <div class="col">
            <p>
                <strong>Why am I seeing this?</strong><br>
                Our system has detected that this is your first login attempt. For security purposes, please update your password to a secure password that you will remember.
            </p>
        </div>
    </div>

    @if(session('errors'))
    @include('partials.errors')
    @endif

    <form name="add-company" action="/first-time-login" method="post" onsubmit="return validate()">
        @csrf
        <div class="row">
            <div class="col">
                <div class="form-group row">
                    <label for="newPassword" class="col-sm-2 col-form-label">New Password:</label>
                    <div class="col-sm-5">
                        <input type="password" id="newPassword" name="newpassword" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="confirmPassword" class="col-sm-2 col-form-label">Confirm Password:</label>
                    <div class="col-sm-5">
                        <input type="password" id="confirmPassword" name="confirmpassword" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <strong>Need help coming up with a password? Use the generator to choose a random, secure password. Secure passwords are hard to guess, which is why punctuation and numbers are included. Please be sure to place this password in a secure location and <span style="text-decoration: underline;">never share your password with anyone!</span></strong>
                </div>
                <div class="form-group row">
                    <label for="randomWord" class="col-sm-2 col-form-label">Random Password:</label>
                    <div class="col-sm-4">
                        <input type="text" id="randomWord" class="form-control" readonly>
                    </div>
                    <div class="col-sm-3">
                        <button type="button" id="random-word" class="btn btn-secondary">Generate Random Words</button>
                    </div>
                </div>
                <div class="form-group row">
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </div>
            </div>
        </div>
    </form>

</div>

<script type="text/javascript">

$(document).ready(function(){
    $('#random-word').click(function(){

        var names = ["list1", "list2", "list3", "list4", "list5"];

        var first = names[Math.floor(Math.random() * names.length)];
        names.splice(names.indexOf(first), 1);

        var second = names[Math.floor(Math.random() * names.length)];
        names.splice(names.indexOf(second), 1);

        var lists = {
            <?php $counter = 1; ?>
            @foreach($lists as $list)
                "list<?php echo $counter; ?>":
                @for ($i = 0; $i < count($list); $i++)

                    <?php
                        if($i == 0) {
                            echo "['" . $list[$i] . "'";
                            continue;
                        }
                        else if($i > 0 && $i < count($list)) {
                            echo ", '" . $list[$i] . "'";
                            continue;
                        }
                    ?>
                @endfor
                <?php 
                    echo "],";
                    $counter++; 
                ?>
            @endforeach
            "punc" : ['!', '@', '#', '$', '%', '^', '&', '*', '(', ')']
        };

        var str = lists[first][Math.floor(Math.random() * 100)] + lists['punc'][Math.floor(Math.random() * 10)] + lists[second][Math.floor(Math.random() * 100)] + lists['punc'][Math.floor(Math.random() * 10)] + Math.floor(Math.random() * 10) + "" + Math.floor(Math.random() * 10) + "" + Math.floor(Math.random() * 10);
        var min = 2;
        var max = 6;

        for(var i = 0; i < rando(min, max); i++) {
            //ASCII range: 33-126
            str += String.fromCharCode(rando(33, 126));
        }

        $('#randomWord').val(str);
    });

    $('#random-word').trigger('click');

    $('#newPassword').popover({title: 'Password Requirements', content: "<ul class=\"list-group\"><li id=\"numChars\" class=\"list-group-item list-group-item-danger\">Must have 8 or more characters.</li><li id=\"digits\" class=\"list-group-item list-group-item-danger\">Must contain at least one digit 0-9</li><li id=\"specialChars\" class=\"list-group-item list-group-item-danger\">Must contain <span style=\"text-decoration: underline;\">one</span> of the following special characters: ! @ # $ % ^ & * ( )</li></ul>", html: true});

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

function rando(min, max) {
    return Math.floor(Math.random() * (max - min) + min);
}

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

    if($('#newPassword').val().length < 8) {
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