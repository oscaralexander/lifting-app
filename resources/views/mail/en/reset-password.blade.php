@extends('mail.layout')

@section('content')
    <h1 class="title">Change password</h1>
    <p class="text">
        Click the button below to set a new password.
    </p>
    <div style="margin: 2.5em 0; text-align: center;">
        <a class="btn" href="{{ $reset_url }}" style="color: #FFFFFF;">Change password</a>
    </div>
    <p class="text">
        Kind regards,<br>
        <br>
        Van der Spek Service Portal team
    </p>
@endsection