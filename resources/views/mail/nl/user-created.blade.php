@extends('mail.layout')

@section('content')
    <h1 class="title">Activeer je account</h1>
    <p class="text">
        U bent door {{ $creator->name }} uitgenodigd voor een Van der Spek Service Portal account.
        Klik op onderstaande knop om uw account te activeren.
    </p>
    <div style="margin: 2.5em 0; text-align: center;">
        <a class="btn" href="{{ route('auth.activate', ['token' => $user->token]) }}" style="color: #FFFFFF;">Account activeren</a>
    </div>
    <p class="text">
        Met vriendelijke groet,<br>
        <br>
        Van der Spek Service Portal team
    </p>
@endsection