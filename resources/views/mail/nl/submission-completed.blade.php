@extends('mail.layout')

@section('content')
    <h1 class="title">Formulier voltooid</h1>
    <p class="text">
        {{ $submission->user->name }} heeft een {{ $submission->form->name }} voltooid voor
        {{ $submission->stockItem->machine->name }} (<a href="{{ route('qr.show', $submission->stockItem->sticker->hash) }}" target="_blank">{{ $submission->stockItem->stock_id }}</a>).
    </p>
    <p class="text">
        Klik op onderstaande knop om het formulier te bekijken.
    </p>
    <div style="margin: 2.5em 0; text-align: center;">
        <a class="btn" href="{{ route('submission.show', ['hash' => $submission->hash, 'stickerHash' => $submission->stockItem->sticker->hash]) }}" style="color: #FFFFFF;">Formulier bekijken</a>
    </div>
@endsection