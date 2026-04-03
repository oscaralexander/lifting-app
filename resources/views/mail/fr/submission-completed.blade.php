@extends('mail.layout')

@section('content')
    <h1 class="title">Formulaire complété</h1>
    <p class="text">
        {{ $submission->user->name }} a complété un(e) {{ $submission->form->name }} pour
        {{ $submission->stockItem->machine->name }} (<a href="{{ route('qr.show', $submission->stockItem->sticker->hash) }}" target="_blank">{{ $submission->stockItem->stock_id }}</a>).
    </p>
    <p class="text">
        Cliquez sur le bouton ci-dessous pour consulter le formulaire.
    </p>
    <div style="margin: 2.5em 0; text-align: center;">
        <a class="btn" href="{{ route('submission.show', ['hash' => $submission->hash, 'stickerHash' => $submission->stockItem->sticker->hash]) }}" style="color: #FFFFFF;">Voir le formulaire</a>
    </div>
@endsection