@use('App\Enums\FieldType')
@use('App\Enums\InspectionType')
@use('App\Lib\FormItems')
@use('App\Models\InspectionObjects\Crane')
@use('App\Models\InspectionObjects\OperatorLift')

@php
    $client      = $inspection->client;
    $object      = $inspection->inspectionObject;
    $form        = $inspection->form;
    $inspectable = $inspection->inspectable;
    $meta        = $inspection->meta_data ?? [];
    $isCrane     = $inspectable instanceof Crane;
@endphp

<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="UTF-8" />
        <title>Keuringsrapport</title>
        <style type="text/css">
            @include('pdf._style-certficate')
        </style>
    </head>
    <body>
        <header class="header">
            <img alt="Lifting Inspections" class="header__logo" src="https://app.liftinginspections.nl/assets/img/pdf/lifting-inspections.svg" />
        </header>
        <div class="split">
            <div class="split__left">
                <div>
                    <h1>
                        <i>Certificaat van</i><br>
                        GOEDKEURING
                    </h1>
                    <div class="intro formatted">
                        <p>
                            Lifting Inspections BV verklaart als door de overheid aangewezen Conformiteits Beoordelende Instelling,
                            dat de hieronder beschreven hijskraan is gekeurd overeenkomstig art. 6d van het Warenwetbesluit Machines
                            en op grond van de bevindingen, weergegeven in het bijbehorende keuringsrapport, is goedgekeurd.
                        </p>
                        <p>
                            Het certificaat heeft betrekking op een periodieke keuring op basis
                            van het certificatieschema met identificatiecode TCVT W3-01: 2025 (24-V11) van de Stichting TCVT.
                        </p>
                    </div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">Merk</th>
                                <td>{{ $object?->manufacturer ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Model</th>
                                <td>{{ $object?->model ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Fabrieksnummer</th>
                                <td>{{ $object?->serial_number ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Bouwjaar</th>
                                <td>{{ $object?->year_manufacture ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Datum keuring</th>
                                <td>{{ $inspection->inspection_date?->translatedFormat('j F Y') ?? '—' }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="onBehalfOf">
                        Namens de Conformiteits Beoordelende Instelling:
                    </p>
                    <div class="signature">
                        <div class="signature__box">
                            <img alt="" class="signature__image" src="https://app.liftinginspections.nl/assets/img/signatures/{{ $inspection->user->id ?? 'default' }}.svg">
                        </div>
                        <p class="signature__text">
                            {{ $inspection->inspector_name }}<br>
                            <i>Inspecteur</i>
                        </p>
                    </div>
                </div>
                <footer class="legal formatted">
                    <p>
                        De volgende TCVT keuring dient uitgevoerd te worden maximaal 24 maanden nadat dit onderzoek heeft plaatsgevonden
                        door een instelling die daartoe door de Minister van Sociale Zaken en Werkgelegenheid is aangewezen.
                        De periodieke keuring dient na maximaal 12 maanden nadat dit onderzoek heeft plaatsgevonden te worden uitgevoerd
                        door een deskundige op de goede staat te laten onderzoeken en doelmatig te beproeven.
                    </p>
                </footer>
            </div>
            <div class="split__right">
                <div>
                    <h4>Datum</h4>
                    <p>{{ $inspection->inspection_date?->translatedFormat('j F Y') ?? '—' }}</p>
                    <h4>Accreditatienummer</h4>
                    <p>I 385, type A</p>
                    <h4>Aanwijzingsbeschikking</h4>
                    <p>
                        Ministerie SZW<br>
                        2025-0000267580
                    </p>
                    <h4>TCVT-nummer</h4>
                    <p>{{ $inspection->sticker_number ?? '—' }}</p>
                    <img alt="TCVT" class="tcvtLogo" src="https://app.liftinginspections.nl/assets/img/pdf/tcvt.svg" />
                </div>
                <div>
                    <h4>Lifting Inspections BV</h4>
                    <p>
                        Nijverheidsweg 1a<br>
                        3433 NP Nieuwegein<br>
                        <br>
                        <a href="mailto:info@liftinginspections.nl">info@liftinginspections.nl</a><br>
                        +31 (0)30 340 00 87
                    </p>
                    <p>KVK 2347241</p>
                    <img alt="RVA i385" class="rva" src="https://app.liftinginspections.nl/assets/img/pdf/rva-i385.svg" />
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="footer__left"></div>
            <div class="footer__center">
                {{ $inspection->outsmart_order_number }}/{{ $inspection->created_at->format('Ymd') }}<br>
            </div>
            <div class="footer__right"></div>
        </footer>
    </body>
</html>
