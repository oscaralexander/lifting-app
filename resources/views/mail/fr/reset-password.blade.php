@extends('mail.layout')

@section('content')
    <h1 class="title">Modifier le mot de passe</h1>
    <p class="text">
        Cliquez sur le bouton ci-dessous pour définir un nouveau mot de passe.
    </p>
    <div style="margin: 2.5em 0; text-align: center;">
        <a class="btn" href="{{ $reset_url }}" style="color: #FFFFFF;">Modifier le mot de passe</a>
    </div>
    <p class="text">
        Cordialement,<br>
        <br>
        L'équipe Van der Spek Service Portal
    </p>
@endsection