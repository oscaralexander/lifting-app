@extends('mail.layout')

@section('content')
    <h1 class="title">Wachtwoord wijzigen</h1>
    <p class="text">
        Klik op onderstaande knop om een nieuw wachtwoord in te stellen.
    </p>
    <div style="margin: 2.5em 0; text-align: center;">
        <a class="btn" href="{{ $reset_url }}" style="color: #FFFFFF;">Wachtwoord wijzigen</a>
    </div>
    <p class="text">
        Met vriendelijke groet,<br>
        <br>
        Van der Spek Service Portal team
    </p>
@endsection