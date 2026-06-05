@use('App\Enums\InspectionType')
@use('App\Models\InspectionObjects\Crane')

@php
    $object      = $inspection->inspectionObject;
    $inspectable = $inspection->inspectable;
    $client      = $inspection->client;
    $meta        = $inspection->meta_data ?? [];
    $isCrane     = $inspectable instanceof Crane;

    $logoDataUri     = 'data:image/svg+xml;base64,' . $logoBase64;
    $reportNumber    = $meta['report_number'] ?? null;
    $stickerNumber   = $meta['sticker_number'] ?? null;
    $nextPeriodical  = $meta['next_periodical_date'] ?? null;
    $nextTcvt        = $meta['next_tcvt_date'] ?? null;

    $docRef = implode('_', array_filter([
        $inspection->created_at->format('Ymd'),
        strtolower($inspection->hash),
        $object?->serial_number ? strtolower(str_replace(' ', '-', $object->serial_number)) : null,
        $object?->manufacturer  ? strtolower(str_replace(' ', '-', $object->manufacturer))  : null,
        $object?->model         ? strtolower(str_replace([' ', '/'], '-', $object->model))  : null,
    ]));

    $result = match (true) {
        $inspection->has_cat_a_deficiencies => 'Categorie A tekortkoming',
        $inspection->has_cat_b_deficiencies => 'Categorie B tekortkoming',
        default => 'Goedgekeurd',
    };

    $typeLabel   = $inspection->type->label();
    $objectLabel = $object ? strtoupper($object->type->label()) : '';

    $coverTitle = match ($inspection->type) {
        InspectionType::TCVT       => 'KEURINGSRAPPORT PERIODIEK / TCVT',
        InspectionType::PERIODICAL => 'KEURINGSRAPPORT PERIODIEK',
    };

    $tableTypeLabel = match ($inspection->type) {
        InspectionType::TCVT       => 'TCVT W3-01:2025 (24-V11)',
        InspectionType::PERIODICAL => 'PERIODIEK',
    };

    $commentData  = collect($inspection->comment_data ?? []);
    $catAComments = $commentData->where('category', 'a')->values();
    $catBComments = $commentData->where('category', 'b')->values();
@endphp
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8" />
    <title>Keuringsrapport</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        @page {
            margin-top: 2.2cm;
            margin-right: 1.5cm;
            margin-bottom: 2.8cm;
            margin-left: 1.5cm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            color: #222;
            line-height: 1.4;
        }

        a { color: #1B3A6B; }

        /* ── RUNNING HEADER ─────────────────────────────────────────
         * position:fixed is relative to the content area (after @page margins),
         * not the physical page edge. left/right 0 aligns with the content area
         * edges, which already sit 1.5cm in from the physical page edges.
         * The cover page (z-index:100) paints over these on page 1.
         */
        .running-header {
            position: fixed;
            top: 0.35cm;
            left: 0;
            right: 0;
            z-index: 10;
            text-align: right;
        }

        .running-header img {
            height: 1cm;
            width: auto;
        }

        /* ── RUNNING FOOTER ───────────────────────────────────────── */
        .running-footer {
            position: fixed;
            bottom: 0.25cm;
            left: 0;
            right: 0;
            z-index: 10;
            font-size: 7.5pt;
            border-top: 1px solid #aaa;
            padding-top: 0.2cm;
        }

        .running-footer table {
            width: 100%;
            border-collapse: collapse;
        }

        .running-footer td {
            vertical-align: top;
            line-height: 1.5;
        }

        .running-footer__left  { width: 40%; }
        .running-footer__mid   { width: 20%; text-align: center; }
        .running-footer__right { width: 40%; text-align: right; }

        .running-footer__docref {
            font-size: 6.5pt;
            color: #666;
            padding-top: 2px;
        }

        /* ── COVER ──────────────────────────────────────────────────
         * z-index:100 (above fixed elements at z-index:10) combined with
         * a white background and min-height:100vh causes the cover to paint
         * over the running header/footer on page 1, hiding them.
         */
        .cover {
            position: relative;
            z-index: 100;
            background: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            break-after: page;
            overflow: hidden;
        }

        .cover::after {
            content: '';
            position: absolute;
            right: -4cm;
            top: -1cm;
            width: 70%;
            height: 110%;
            background-image: url('{{ $logoDataUri }}');
            background-repeat: no-repeat;
            background-size: contain;
            background-position: center right;
            opacity: 0.1;
            pointer-events: none;
        }

        .cover__logo {
            height: 1.8cm;
            width: auto;
            position: relative;
            z-index: 1;
        }

        .cover__title-wrap {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
        }

        .cover__title {
            color: #1B3A6B;
            font-size: 22pt;
            font-weight: bold;
            text-align: center;
            line-height: 1.3;
        }

        .cover__disclaimer {
            font-size: 7pt;
            color: #555;
            margin-top: 2cm;
            padding-bottom: 1cm;
            position: relative;
            z-index: 1;
        }

        /* ── PAGE BREAK ─────────────────────────────────────────── */
        .page-break-after  { break-after: page; }
        .page-break-before { break-before: page; }

        /* ── INTRODUCTION ───────────────────────────────────────── */
        .intro { break-after: page; }

        .intro h2 {
            font-size: 16pt;
            font-weight: normal;
            color: #222;
            border-bottom: 1px solid #ccc;
            padding-bottom: 0.25cm;
            margin-bottom: 0.4cm;
        }

        .intro h3 {
            font-size: 9pt;
            font-weight: bold;
            margin: 0.35cm 0 0.1cm;
        }

        .intro p { margin: 0.15cm 0; font-size: 8.5pt; }

        .intro ul {
            margin: 0.1cm 0 0.15cm 0.8cm;
            font-size: 8.5pt;
        }

        .intro li { list-style: disc; margin: 0.05cm 0; }

        /* ── INFO TABLES ────────────────────────────────────────── */
        .section { margin-bottom: 0.35cm; }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table__header {
            background-color: #1B3A6B;
            color: #fff;
            font-weight: bold;
            font-size: 8pt;
            text-transform: uppercase;
            padding: 5px 8px;
        }

        .info-table td {
            padding: 4px 8px;
            border-bottom: 1px solid #d8e0ee;
            vertical-align: top;
        }

        .info-table__label {
            width: 35%;
            color: #333;
        }

        /* ── LEGEND ─────────────────────────────────────────────── */
        .legend-wrap {
            display: flex;
            justify-content: flex-end;
            margin-top: 0.8cm;
        }

        .legend-table {
            border-collapse: collapse;
            font-size: 8.5pt;
        }

        .legend-table__header {
            background-color: #1B3A6B;
            color: #fff;
            font-weight: bold;
            font-size: 8pt;
            padding: 5px 8px;
            text-align: center;
        }

        .legend-table td {
            padding: 4px 8px;
            border-bottom: 1px solid #d8e0ee;
            vertical-align: middle;
        }

        .legend-table .check-cell {
            width: 26px;
            text-align: center;
            font-size: 11pt;
        }

        /* ── CHECKLIST ──────────────────────────────────────────── */
        .checklist {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0.4cm;
        }

        .checklist__header td {
            background-color: #1B3A6B;
            color: #fff;
            font-weight: bold;
            font-size: 8pt;
            padding: 5px 8px;
        }

        .checklist__header .num-col {
            width: 26px;
            text-align: center;
        }

        .checklist__row {
            break-inside: avoid;
        }

        .checklist__row td {
            padding: 4px 6px;
            border-bottom: 1px solid #d8e0ee;
            vertical-align: top;
            font-size: 8.5pt;
        }

        .checklist__code {
            width: 44px;
            white-space: nowrap;
            color: #444;
            font-size: 8pt;
        }

        .checklist__check {
            width: 26px;
            text-align: center;
            font-size: 11pt;
        }

        /* ── DEFICIENCIES ───────────────────────────────────────── */
        .defic-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0.5cm;
        }

        .defic-table__header {
            background-color: #1B3A6B;
            color: #fff;
            font-weight: bold;
            font-size: 8pt;
            padding: 5px 8px;
        }

        .defic-table__subheader {
            background-color: #2C4E82;
            color: #fff;
            font-size: 8pt;
            padding: 4px 8px;
        }

        .defic-table td {
            padding: 4px 8px;
            border-bottom: 1px solid #d8e0ee;
            vertical-align: top;
            font-size: 8.5pt;
            min-height: 18px;
        }

        .defic-table__code { width: 80px; }
    </style>
</head>
<body>

{{-- ─── RUNNING HEADER (position:fixed, repeats on every page) ─────────────── --}}
<div class="running-header" aria-hidden="true">
    <img src="{{ $logoDataUri }}" alt="" />
</div>

{{-- ─── RUNNING FOOTER (position:fixed, repeats on every page) ─────────────── --}}
<div class="running-footer" aria-hidden="true">
    <table>
        <tr>
            <td class="running-footer__left">
                Lifting Inspections B.V.<br />
                Nijverheidsweg 1A<br />
                3433NP Nieuwegein<br />
                KvK-nummer 23047245
            </td>
            <td class="running-footer__mid">R.01</td>
            <td class="running-footer__right">RvA I385</td>
        </tr>
        <tr>
            <td colspan="3" class="running-footer__docref">Doc::{{ $docRef }}</td>
        </tr>
    </table>
</div>

{{-- ─── COVER PAGE ────────────────────────────────────────────────────────── --}}
<div class="cover">
    <img class="cover__logo" src="{{ $logoDataUri }}" alt="Lifting Inspections" />

    <div class="cover__title-wrap">
        <div class="cover__title">
            {{ $coverTitle }}<br />
            {{ $objectLabel }}
        </div>
    </div>

    <p class="cover__disclaimer">
        De inhoud van dit rapport is in overeenstemming met het TCVT-Keuringsschema
        identificatiecode:(TCVT W3-01: 2025 (24-V11)) voor het periodiek keuren van hijskranen.
    </p>
</div>

{{-- ─── INLEIDING ─────────────────────────────────────────────────────────── --}}
<div class="intro">
    <h2>Inleiding</h2>

    <p>Lifting Inspections B.V. is in het bezit van een EN ISO/IEC 17020:2012, type A accreditatie, van de Raad voor Accreditatie (RvA) met registratienummer I385 en is door de Minister van Sociale Zaken en Werkgelegenheid (SZW) aangewezen als NL-conformiteitsbeoordelingsinstantie (NL-CBI) die is bevoegd voor het uitvoeren van periodieke keuringen van hijskranen, zoals beschreven in het Warenwetbesluit machines, artikel 6d, 6ea en 6f en de Warenwetregeling machines, artikel 2. Aanwijzingsbeschikking Ministerie SZW: 2025-0000267580.</p>

    <h3>Nederlandse wetgeving</h3>
    <p>Hijskranen die behoren tot een bij ministeriële regeling omschreven categorie moeten conform het Warenwetbesluit machines gekeurd worden door een door de minister aangewezen instelling.</p>
    <p>Voor de eerste maal dient dit te geschieden na verloop van ten hoogste 24 maanden na de eerste ingebruikneming en vervolgens telkens na verloop van ten hoogste 24 maanden.</p>
    <p>(Warenwetbesluit machines artikel 6d). Voor de tussenliggende jaren dient overeenkomstig artikel 6d van het Warenwetbesluit machines de kraan te worden gekeurd door een deskundige natuurlijke persoon, rechtspersoon of instelling.</p>

    <h3>TCVT</h3>
    <p>Het keuringsschema is opgesteld door de TCVT, identificatiecode: TCVT W3-01: 2025 (24-V11). Dit betreft de verplichte tweejaarlijkse keuringen. Meer informatie is te vinden op: <a href="https://www.tcvt.nl">www.tcvt.nl</a>.</p>

    <h3>Periodieke keuring</h3>
    <p>Tijdens de periodieke keuring worden de veranderingen die tijdens het gebruik van de machine zijn ontstaan door slijtage, veroudering of door het uitvoeren van onderhoud en reparatie, geïnspecteerd en worden de veiligheidsvoorzieningen functioneel getest. De periodieke keuring behelst de staat van de machine op het moment van keuren. De periodieke keuring vindt plaats zonder demontage van delen van de machine. Er wordt ook nagegaan of er een EG-verklaring van overeenstemming aanwezig is van de hijskraan.</p>

    <h3>Resultaat keuring</h3>
    <p><b>Geen tekortkomingen</b></p>
    <ul>
        <li>Afgifte TCVT Certificaat van Goedkeuring gedateerd op de keuringsdatum, met de TCVT Goedkeuringssticker.</li>
    </ul>

    <p>Tijdens de periodieke keuring kan er een categorie A- of een categorie B-tekortkoming waargenomen worden.</p>

    <p><b>Categorie A-tekortkoming:</b></p>
    <ul>
        <li>Een tekortkoming met een direct gevaar voor elektrocutie, gevaar voor omvallen van de machine, gevaar voor bezwijken van dragende delen van de machine of voor het ongewild omlaag komen van de last.</li>
        <li>Er is een directe voorziening aan de machine noodzakelijk en gebruik van de machine is uit veiligheidsoogpunt dan ook onverantwoord en de machine moet buiten gebruik worden gesteld.</li>
        <li>De opdrachtgever geeft Lifting Inspections B.V. zo spoedig mogelijk, doch uiterlijk binnen 2 maanden na de keuring opdracht voor een nacontrole op de uitgevoerde reparaties. Lifting Inspections B.V. voert de nacontrole uit, tenzij de aard van de tekortkoming een schriftelijke of digitale afhandeling rechtvaardigt (afmelden@liftinginspections.nl).</li>
        <li>Als de afmelding niet binnen de gestelde termijn is geschied, dan mag geen certificaat worden afgegeven. In dat geval volgt altijd een volledige nieuwe keuring indien de opdrachtgever de machine in gebruik wil nemen. Indien de reparaties als adequaat worden gekwalificeerd, verstrekt Lifting Inspections B.V. aan de opdrachtgever het TCVT Certificaat van Goedkeuring.</li>
    </ul>

    <p><b>Categorie B-tekortkoming:</b></p>
    <ul>
        <li>De waargenomen tekortkoming betreft geen direct gevaar voor de veiligheid.</li>
        <li>Een tekortkoming die in de nabije toekomst niet zal leiden tot een categorie A-tekortkoming.</li>
        <li>De opdrachtgever rapporteert aan Lifting Inspections B.V. zo spoedig mogelijk, doch uiterlijk binnen 2 maanden na de keuring, schriftelijk of digitaal (incl. bewijslast), dat de tekortkoming is verholpen (afmelden@liftinginspections.nl).</li>
        <li>Als de afmelding niet binnen de gestelde termijn is geschied, dan mag geen certificaat worden afgegeven. In dat geval volgt altijd een volledige nieuwe keuring indien de opdrachtgever de machine in gebruik wil nemen. Indien de reparaties als adequaat worden gekwalificeerd, verstrekt Lifting Inspections B.V. aan de opdrachtgever het TCVT Certificaat van Goedkeuring.</li>
    </ul>

    <p>De bevindingen van de keurmeester worden direct na de keuring aan de opdrachtgever schriftelijk gemeld door inschrijving in het kraanboek.</p>
</div>

{{-- ─── KEY DATA TABLES ────────────────────────────────────────────────────── --}}
<div class="page-break-after">

    {{-- TYPE KEURING --}}
    <div class="section">
        <table class="info-table">
            <thead>
                <tr>
                    <td class="info-table__header" colspan="2">
                        TYPE KEURING: {{ $tableTypeLabel }} {{ $objectLabel }}
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="info-table__label">Rapportnummer</td>
                    <td>{{ $reportNumber ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="info-table__label">Keuringsdatum</td>
                    <td>{{ $inspection->created_at->isoFormat('D-M-YYYY') }}</td>
                </tr>
                <tr>
                    <td class="info-table__label">Inspecteur</td>
                    <td>{{ $inspection->user->name }}</td>
                </tr>
                <tr>
                    <td class="info-table__label">Bevindingen</td>
                    <td>{{ $result }}</td>
                </tr>
                <tr>
                    <td class="info-table__label">Volgende periodieke keuring voor</td>
                    <td>{{ $nextPeriodical ?? '—' }}</td>
                </tr>
                @if ($inspection->type === InspectionType::TCVT)
                    <tr>
                        <td class="info-table__label">Volgende TCVT keuring voor</td>
                        <td>{{ $nextTcvt ?? '—' }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="info-table__label">Sticker nummer</td>
                    <td>{{ $stickerNumber ?? '—' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- OPDRACHTGEVER --}}
    @if ($client)
        <div class="section">
            <table class="info-table">
                <thead>
                    <tr>
                        <td class="info-table__header" colspan="2">OPDRACHTGEVER</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="info-table__label">Naam</td>
                        <td>{{ $client->name }}</td>
                    </tr>
                    <tr>
                        <td class="info-table__label">Adres</td>
                        <td>{{ $client->address }}</td>
                    </tr>
                    <tr>
                        <td class="info-table__label">Postcode</td>
                        <td>{{ $client->postal_code }}</td>
                    </tr>
                    <tr>
                        <td class="info-table__label">Plaats</td>
                        <td>{{ $client->city }}</td>
                    </tr>
                    @if ($client->contact_name)
                        <tr>
                            <td class="info-table__label">Contactpersoon</td>
                            <td>{{ $client->contact_name }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    @endif

    {{-- PROJECTGEGEVENS --}}
    @if ($inspection->project_name)
        <div class="section">
            <table class="info-table">
                <thead>
                    <tr>
                        <td class="info-table__header" colspan="2">PROJECTGEGEVENS</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="info-table__label">Naam</td>
                        <td>{{ $inspection->project_name }}</td>
                    </tr>
                    @if ($inspection->project_address)
                        <tr>
                            <td class="info-table__label">Adres</td>
                            <td>{{ $inspection->project_address }}</td>
                        </tr>
                    @endif
                    @if ($inspection->project_postal_code)
                        <tr>
                            <td class="info-table__label">Postcode</td>
                            <td>{{ $inspection->project_postal_code }}</td>
                        </tr>
                    @endif
                    @if ($inspection->project_city)
                        <tr>
                            <td class="info-table__label">Plaats</td>
                            <td>{{ $inspection->project_city }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    @endif

    {{-- INSPECTION OBJECT --}}
    @if ($object)
        <div class="section">
            <table class="info-table">
                <thead>
                    <tr>
                        <td class="info-table__header" colspan="2">{{ $objectLabel }}</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="info-table__label">Fabrikant</td>
                        <td>{{ $object->manufacturer }}</td>
                    </tr>
                    <tr>
                        <td class="info-table__label">Type</td>
                        <td>{{ $object->model }}</td>
                    </tr>
                    <tr>
                        <td class="info-table__label">Fabrieksnummer</td>
                        <td>{{ $object->serial_number }}</td>
                    </tr>
                    @if ($object->year_manufacture)
                        <tr>
                            <td class="info-table__label">Bouwjaar</td>
                            <td>{{ $object->year_manufacture }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    @endif

</div>

{{-- ─── CRANE CONFIGURATION ────────────────────────────────────────────────── --}}
@if ($isCrane)
    @php $crane = $inspectable; @endphp
    <div class="page-break-after">

        <div class="section">
            <table class="info-table">
                <thead>
                    <tr><td class="info-table__header" colspan="2">CONFIGURATIE</td></tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="info-table__label">Opstelling</td>
                        <td>{{ $crane->base_configuration?->label() ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="info-table__label">Afmetingen (indien van toepassing)</td>
                        <td>
                            @if ($crane->base_length && $crane->base_width)
                                {{ number_format($crane->base_length, 2, '.', '') }} × {{ number_format($crane->base_width, 2, '.', '') }} mtr.
                            @else
                                — mtr.
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <table class="info-table">
                <thead>
                    <tr><td class="info-table__header" colspan="2">TOREN</td></tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="info-table__label">Haakhoogte</td>
                        <td>{{ $crane->hook_height ? number_format($crane->hook_height, 2, '.', '') . ' mtr.' : '—' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <table class="info-table">
                <thead>
                    <tr><td class="info-table__header" colspan="2">VOORGIEK</td></tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="info-table__label">Giekuitvoering</td>
                        <td>
                            @if ($crane->boom_is_trolley)
                                Met loopkat
                            @elseif ($crane->boom_is_luffing)
                                Luffend
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="info-table__label">Gieklengte</td>
                        <td>{{ $crane->boom_length ? number_format($crane->boom_length, 2, '.', '') . ' mtr.' : '—' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <table class="info-table">
                <thead>
                    <tr><td class="info-table__header" colspan="2">CENTRAALBALLAST</td></tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="info-table__label">Gewicht (indien van toepassing)</td>
                        <td>{{ $crane->central_ballast ? number_format($crane->central_ballast, 1, '.', '') . ' ton' : 'n.v.t.' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <table class="info-table">
                <thead>
                    <tr><td class="info-table__header" colspan="2">CONTRABALLAST</td></tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="info-table__label">Gewicht</td>
                        <td>{{ $crane->counter_ballast ? number_format($crane->counter_ballast, 1, '.', '') . ' ton' : '—' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- LEGEND --}}
        <div class="legend-wrap">
            <table class="legend-table">
                <thead>
                    <tr>
                        <td class="legend-table__header" style="text-align:left;">UITLEG BEVINDINGEN</td>
                        <td class="legend-table__header">1</td>
                        <td class="legend-table__header">2</td>
                        <td class="legend-table__header">3</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>In orde.</td>
                        <td class="check-cell">☒</td>
                        <td class="check-cell">☐</td>
                        <td class="check-cell">☐</td>
                    </tr>
                    <tr>
                        <td>Niet in orde.</td>
                        <td class="check-cell">☐</td>
                        <td class="check-cell">☒</td>
                        <td class="check-cell">☐</td>
                    </tr>
                    <tr>
                        <td>Niet van toepassing.</td>
                        <td class="check-cell">☐</td>
                        <td class="check-cell">☐</td>
                        <td class="check-cell">☒</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
@endif

{{-- ─── INSPECTION CHECKLIST ───────────────────────────────────────────────── --}}
@foreach ($inspection->form->fieldGroups as $fieldGroup)
    <table class="checklist">
        <thead>
            <tr class="checklist__header">
                <td colspan="2"><b>{{ $fieldGroup->number }}</b> {{ $fieldGroup->name }}</td>
                <td class="num-col">1</td>
                <td class="num-col">2</td>
                <td class="num-col">3</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($fieldGroup->fields as $field)
                @php $answer = $inspection->getAnswerForField($field->pivot->id); @endphp
                <tr class="checklist__row">
                    <td class="checklist__code">{{ $field->number }}</td>
                    <td>{!! nl2br(e($field->label)) !!}</td>
                    <td class="checklist__check">{{ $answer === '1' ? '☒' : '☐' }}</td>
                    <td class="checklist__check">{{ $answer === '2' ? '☒' : '☐' }}</td>
                    <td class="checklist__check">{{ $answer === '3' ? '☒' : '☐' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endforeach

{{-- ─── DEFICIENCIES ───────────────────────────────────────────────────────── --}}
<div class="page-break-before">

    <table class="defic-table">
        <thead>
            <tr>
                <td class="defic-table__header" colspan="2">2200 TOELICHTING VAN DE WAARGENOMEN TEKORTKOMINGEN</td>
            </tr>
            <tr>
                <td class="defic-table__subheader defic-table__code">Volgnummer</td>
                <td class="defic-table__subheader">Categorie A-afwijkingen</td>
            </tr>
        </thead>
        <tbody>
            @if ($catAComments->isEmpty())
                <tr>
                    <td class="defic-table__code"></td>
                    <td>Er zijn geen categorie A tekortkomingen geconstateerd</td>
                </tr>
            @else
                @foreach ($catAComments as $comment)
                    <tr>
                        <td class="defic-table__code">{{ $comment['number'] ?? '' }}</td>
                        <td>{{ $comment['description'] ?? '' }}</td>
                    </tr>
                @endforeach
            @endif
            @for ($i = 0; $i < 8; $i++)
                <tr><td class="defic-table__code">&nbsp;</td><td>&nbsp;</td></tr>
            @endfor
        </tbody>
    </table>

    <table class="defic-table">
        <thead>
            <tr>
                <td class="defic-table__subheader defic-table__code"></td>
                <td class="defic-table__subheader">Categorie B-afwijkingen</td>
            </tr>
        </thead>
        <tbody>
            @if ($catBComments->isEmpty())
                <tr>
                    <td class="defic-table__code"></td>
                    <td>Er zijn geen categorie B tekortkomingen geconstateerd</td>
                </tr>
            @else
                @foreach ($catBComments as $comment)
                    <tr>
                        <td class="defic-table__code">{{ $comment['number'] ?? '' }}</td>
                        <td>{{ $comment['description'] ?? '' }}</td>
                    </tr>
                @endforeach
            @endif
            @for ($i = 0; $i < 8; $i++)
                <tr><td class="defic-table__code">&nbsp;</td><td>&nbsp;</td></tr>
            @endfor
        </tbody>
    </table>

</div>

</body>
</html>
