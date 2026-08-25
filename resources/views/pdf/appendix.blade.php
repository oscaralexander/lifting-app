@use('Illuminate\Support\Facades\Storage')

@php
    $images = array_values(array_filter(
        (array) ($inspection->images ?? []),
        fn ($image) => is_string($image) && $image !== '',
    ));
@endphp
<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="UTF-8" />
        <title>Bijlage</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100..900&display=swap" rel="stylesheet">
        <style type="text/css">
            @include('pdf._style')
        </style>
    </head>
    <body>
        <header class="header">
            <img alt="Lifting Inspections" class="header__logo" src="http://app.liftinginspections.nl/assets/img/pdf/lifting-inspections-icon.svg" />
        </header>
        <footer class="footer">
            <div class="footer__left">
                Lifting Inspections B.V.<br />
                Nijverheidsweg 1A<br />
                3433NP Nieuwegein<br />
                KvK 23047245
            </div>
            <div class="footer__center">
                {{ $inspection->outsmart_order_number }}/{{ $inspection->created_at->format('Ymd') }}<br>
                p. <span class="footer__pageNo"></span>
            </div>
            <div class="footer__right">
                <a href="https://www.rva.nl/alle-geaccrediteerden/i385/" target="_blank"><img alt="RVA Inspectie I385" class="footer__logo" src="http://app.liftinginspections.nl/assets/img/pdf/rva-i385.svg"></a>
            </div>
        </footer>
        <div class="cover">
            <header class="cover__header">
                <img alt="Lifting Inspections" class="cover__logo" src="http://app.liftinginspections.nl/assets/img/pdf/lifting-inspections.svg">
            </header>
            <div class="cover__content">
                <h1>Bijlage</h1>
                <h2>{{ $inspection->outsmart_order_number }}</h2>
            </div>
            <footer class="cover__footer">
                De inhoud van dit rapport is in overeenstemming met het TCVT-keuringsschema identificatiecode: TCVT W3-01: 2025 (24-V11) voor het periodiek keuren van hijskranen.
            </footer>
        </div>
        <!-- Comments -->
        <div class="intro intro--end formatted">
            <h2>Opmerkingen</h2>
            @if ($inspection->comment)
                <p>{!! nl2br(e($inspection->comment)) !!}</p>
            @endif
            @if (count($images))
                <div class="deficiency__photos">
                    @foreach ($images as $image)
                        <img alt="" class="deficiency__photo" src="{{ Str::startsWith($image, ['http://', 'https://']) ? $image : Storage::disk('public')->url($image) }}">
                    @endforeach
                </div>
            @endif
        </div>
    </body>
</html>
