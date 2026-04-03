@extends('mail.layout')

@section('content')
    <h1 class="title">Activate your account</h1>
    <p class="text">
        You have been invited by {{ $creator->name }} for a Van der Spek Service Portal account.
        Click the button below to activate your account.
    </p>
    <div style="margin: 2.5em 0; text-align: center;">
        <a class="btn" href="{{ route('auth.activate', ['token' => $user->token]) }}" style="color: #FFFFFF;">Activate account</a>
    </div>
    <p class="text">
        Kind regards,<br>
        <br>
        Van der Spek Service Portal team
    </p>
@endsection