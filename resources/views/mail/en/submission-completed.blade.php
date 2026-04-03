@extends('mail.layout')

@section('content')
    <h1 class="title">Form submission completed</h1>
    <p class="text">
        {{ $submission->user->name }} has completed a {{ $submission->form->name }} for
        {{ $submission->stockItem->machine->name }} (<a href="{{ route('qr.show', $submission->stockItem->sticker->hash) }}" target="_blank">{{ $submission->stockItem->stock_id }}</a>).
    </p>
    <p class="text">
        Click the button below to view the form.
    </p>
    <div style="margin: 2.5em 0; text-align: center;">
        <a class="btn" href="{{ route('submission.show', ['hash' => $submission->hash, 'stickerHash' => $submission->stockItem->sticker->hash]) }}" style="color: #FFFFFF;">View form</a>
    </div>
@endsection