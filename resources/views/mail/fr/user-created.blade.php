@extends('mail.layout')

@section('content')
    <h1 class="title">Activez votre compte</h1>
    <p class="text">
        Vous avez été invité par {{ $creator->name }} pour un compte Van der Spek Service Portal.
        Cliquez sur le bouton ci-dessous pour activer votre compte.
    </p>
    <div style="margin: 2.5em 0; text-align: center;">
        <a class="btn" href="{{ route('auth.activate', ['token' => $user->token]) }}" style="color: #FFFFFF;">Activer le compte</a>
    </div>
    <p class="text">
        Cordialement,<br>
        <br>
        L'équipe Van der Spek Service Portal
    </p>
@endsection