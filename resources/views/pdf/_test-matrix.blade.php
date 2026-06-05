@use('App\Enums\InspectionObject\Crane\BaseConfiguration')

@php
    $matrix = $inspection->matrix ?? [];

    $num = fn ($v) => ($v === null || trim((string) $v) === '') ? null : (float) str_replace(',', '.', (string) $v);

    $deviation = function ($permissible, $measured) use ($num) {
        $p = $num($permissible);
        $m = $num($measured);

        if ($p === null || $m === null || $p == 0.0) {
            return null;
        }

        return (($m - $p) / $p) * 100;
    };

    $formatDeviation = fn ($d) => $d === null ? '—' : number_format($d, 2, ',', '') . '%';
    $deviationClass = fn ($d) => $d === null ? 'neutral' : ($d >= 10 ? 'failed' : 'passed');

    $check = '<img alt="" height="12" src="https://app.liftinginspections.nl/assets/img/pdf/check.svg" width="12">';

    $baseConfiguration = $inspectable?->base_configuration;
@endphp

<div class="matrix">
    <h2>@lang('inspections.form.heading_test_matrix')</h2>
    <table>
        <thead>
            <tr>
                <th class="border-bottom border-right rotate" scope="col" rowspan="4"><div>Volgnummer beproeving</div></th>
                <th class="border-right heading" scope="col" colspan="12">Gegevens volgens hijstabel</th>
                <th class="heading" scope="col" colspan="10">Beproeving</th>
            </tr>
            <tr>
                {{-- Gegevens volgens hijstabel --}}
                <th class="border-right subheading" scope="col" colspan="4">Opstelling</th>
                <th class="border-right subheading" scope="col" colspan="3">Giek</th>
                <th class="border-right subheading" scope="col" colspan="2">Ballast</th>
                <th class="border-left rotate" scope="col" rowspan="2"><div>Aantal parten hijskabel</div></th>
                <th scope="col">Zwenk&shy;hoek</th>
                <th class="border-right rotate" scope="col" rowspan="2"><div>LMB Code / Gang</div></th>
                {{-- Beproeving --}}
                <th class="border-right" scope="col" colspan="6">LMB</th>
                <th class="border-right" scope="col" colspan="3">LB</th>
                <th class="border-left rotate" scope="col" rowspan="2"><div>Akkoord</div></th>
            </tr>
            <tr>
                <th class="rotate" scope="col"><div>Aan fundatie</div></th>
                <th class="rotate" scope="col"><div>Op kruisframe</div></th>
                <th class="rotate" scope="col"><div>Op rails</div></th>
                <th class="border-right rotate" scope="col"><div>Op stempels</div></th>
                {{-- Giek --}}
                <th class="rotate" scope="col"><div>Gieklengte (m)</div></th>
                <th class="rotate" scope="col"><div>Giekhoek (º)</div></th>
                <th class="border-right rotate" scope="col"><div>Haakhoogte (m)</div></th>
                {{-- Ballast --}}
                <th class="rotate" scope="col"><div>Massa centraal ballast (t/kg)</div></th>
                <th class="border-right rotate" scope="col"><div>Massa contraballast (t/kg)</div></th>
                <th class="rotate" scope="col"><div>R = 360º, A = Achter<br>Z = Zij, V = Voor</div></th>
                {{-- LMB --}}
                <th class="rotate" scope="col"><div>Proeflast (t/kg)</div></th>
                <th class="rotate" scope="col"><div>Toelaatbare vlucht bij proeflast</div></th>
                <th class="rotate" scope="col"><div>LMB treed in werking<br>bij katten uit (m)</div></th>
                <th class="rotate" scope="col"><div>LMB treed in werking<br>bij hijsen (m)</div></th>
                <th class="rotate" scope="col"><div>Toelaatbare bedrijfslast<br>bij kolom 18</div></th>
                <th class="border-right rotate" scope="col"><div>Afwijking LMB</div></th>
                {{-- LB --}}
                <th class="rotate" scope="col"><div>LB treedt in werking bij (t/kg)</div></th>
                <th class="rotate" scope="col"><div>Toelaatbare bedrijfslast<br>bij kolom 20</div></th>
                <th class="border-right rotate" scope="col"><div>Afwijking LB</div></th>
            </tr>
            <tr>
                <th scope="col">1</th>
                <th scope="col">2</th>
                <th scope="col">3</th>
                <th class="border-right" scope="col">4</th>
                <th scope="col">5</th>
                <th scope="col">6</th>
                <th class="border-right" scope="col">7</th>
                <th scope="col">8</th>
                <th class="border-right" scope="col">9</th>
                <th scope="col">10</th>
                <th scope="col">11</th>
                <th class="border-right" scope="col">12</th>
                <th scope="col">13</th>
                <th scope="col">14</th>
                <th scope="col">15.1</th>
                <th scope="col">15.2</th>
                <th scope="col">16</th>
                <th class="border-right" scope="col">17</th>
                <th scope="col">18</th>
                <th scope="col">19</th>
                <th class="border-right" scope="col">20</th>
                <th scope="col">21</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 0; $i < 4; $i++)
                @php
                    $row = $matrix[$i] ?? [];

                    $deviationLmb = $deviation($row['col_14'] ?? null, $row['col_16'] ?? null);
                    $deviationLb = $deviation($row['col_15'] ?? null, $row['col_19'] ?? null);

                    $isNeutral = $deviationLmb === null && $deviationLb === null;
                    $isFailed = ! $isNeutral && ($deviationLmb >= 10 || $deviationLb >= 10);

                    $resultText = $isNeutral ? '—' : ($isFailed ? 'NEE' : 'JA');
                    $resultClass = $isNeutral ? 'neutral' : ($isFailed ? 'failed' : 'passed');
                @endphp
                <tr>
                    <th class="border-right" scope="row">{{ $i + 1 }}</th>
                    <td>{!! $baseConfiguration === BaseConfiguration::FOUNDATION_ANCHORS ? $check : '' !!}</td>{{-- 1 --}}
                    <td>{!! $baseConfiguration === BaseConfiguration::CROSS_FRAME ? $check : '' !!}</td>{{-- 2 --}}
                    <td>{!! $baseConfiguration === BaseConfiguration::RAIL_TRAVELLING ? $check : '' !!}</td>{{-- 3 --}}
                    <td class="border-right">{!! $inspectable?->outrigger_type ? $check : '' !!}</td>{{-- 4 --}}
                    <td>{{ $inspectable?->boom_length ? format_number($inspectable->boom_length) : '' }}</td>{{-- 5 --}}
                    <td>{{ $row['col_6'] ?? '' }}</td>{{-- 6 --}}
                    <td class="border-right">{{ $inspectable?->hook_height ? format_number($inspectable->hook_height) : '' }}</td>{{-- 7 --}}
                    <td>{{ $inspectable?->central_ballast ? format_number($inspectable->central_ballast) : '' }}</td>{{-- 8 --}}
                    <td class="border-right">{{ $inspectable?->counter_ballast ? format_number($inspectable->counter_ballast) : '' }}</td>{{-- 9 --}}
                    <td>{{ $row['col_10'] ?? '' }}</td>{{-- 10 --}}
                    <td>{{ $row['col_11'] ?? '' }}</td>{{-- 11 --}}
                    <td class="border-right">{{ $row['col_12'] ?? '' }}</td>{{-- 12 (LMB Code / Gang) --}}
                    <td>{{ $row['col_16'] ?? '' }}</td>{{-- 13 (Proeflast) --}}
                    <td>{{ $row['col_18'] ?? '' }}</td>{{-- 14 (Toelaatbare vlucht bij proeflast) --}}
                    <td>{{ $row['col_17_1'] ?? '' }}</td>{{-- 15.1 --}}
                    <td>{{ $row['col_17_2'] ?? '' }}</td>{{-- 15.2 --}}
                    <td>{{ $row['col_14'] ?? '' }}</td>{{-- 16 (Toelaatbare bedrijfslast bij kolom 18) --}}
                    <td class="border-right {{ $deviationClass($deviationLmb) }}">{{ $formatDeviation($deviationLmb) }}</td>{{-- 17 (Afwijking LMB) --}}
                    <td>{{ $row['col_19'] ?? '' }}</td>{{-- 18 (LB treedt in werking bij) --}}
                    <td>{{ $row['col_15'] ?? '' }}</td>{{-- 19 (Toelaatbare bedrijfslast bij kolom 20) --}}
                    <td class="border-right {{ $deviationClass($deviationLb) }}">{{ $formatDeviation($deviationLb) }}</td>{{-- 20 (Afwijking LB) --}}
                    <td class="result {{ $resultClass }}">{{ $resultText }}</td>{{-- 21 (Akkoord) --}}
                </tr>
            @endfor
        </tbody>
    </table>
</div>
