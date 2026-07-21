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
                            Lifting Inspections BV verklaart als door de overheid aangewezen Conformiteits Beoordelende Instelling, dat de hieronder beschreven hijskraan is gekeurd overeenkomstig art. 6d van het Warenwetbesluit Machines en op grond van de bevindingen, weergegeven in het bijbehorende keuringsrapport, is goedgekeurd.
                        </p>
                        <p>
                            Het certificaat heeft betrekking op een periodieke keuring op basis
                            van het vigerende certificatieschema voor de betrokken kraancategorie
                            (W3-01/18-083(3)) van de Stichting TCVT.
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
                                <th scope="row">Parknummer</th>
                                <td>NVT</td>
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
                            <img alt="" class="signature__image" src="https://app.liftinginspections.nl/assets/img/signatures/signature.svg" />
                        </div>
                        <p class="signature__text">
                            {{ $inspection->inspector_name }}<br>
                            <i>Keurmeester</i>
                        </p>
                    </div>
                </div>
                <footer class="footer formatted">
                    <p>
                        Ten hoogste 24 maanden nadat het onderzoek heeft plaatsgevonden dient opnieuw een onderzoek plaats te vinden,
                        dat uitgevoerd wordt door een instelling die daartoe door de Minister van Sociale Zaken en Werkgelegenheid is aangewezen.
                        Daarnaast bestaat de verplichting de kraan ten minste eenmaal per jaar door een deskundige op de goede staat te
                        laten onderzoeken en doelmatig te beproeven.
                    </p>
                    <p>
                        De eigenaar c.q. opdrachtgever moet toestaan dat de Conformiteits Beoordelende Instelling op de machine op een voor
                        derden duidelijk zichtbare plaats de TCVT Goedkeuringssticker conform het Reglement TCVT-Beeldmerk VT-800 aanbrengt.
                    </p>
                    <p>
                        Ingevolge de Algemene Wet Bestuursrecht kan tegen dit certificaat van goedkeuring bezwaar worden ingediend bij Lifting Inspections BV te Nieuwegein.
                        Binnen zes weken na de datum van verzending van dit certificaat moet dit bezwaarschrift worden ingediend.
                        In het bezwaarschrift dient aangegeven te worden waarom dit certificaat niet juist bevonden wordt.
                        Verzocht wordt bij het bezwaarschrift een kopie van dit certificaat en van evt. andere op de zaak betrekking hebbende stukken te voegen.
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
                        2025-0000109593
                    </p>
                    <h4>TCVT-nummer</h4>
                    <p>00-036.335</p>
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
    </body>
</html>
