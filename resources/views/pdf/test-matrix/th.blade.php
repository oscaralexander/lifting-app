<div class="matrix">
    <h2>@lang('inspections.form.heading_test_matrix')</h2>
    <table>
        <thead>
            <tr>
                <th class="border-bottom border-right rotate" scope="col" rowspan="5"><div>Volgnummer</div></th>
                <th class="border-right heading" scope="col" colspan="7">Gegevens volgens hijstabel</th>
                <th class="heading" scope="col" colspan="12">Beproeving</th>
            </tr>
            <tr>
                <th class="border-right subheading" scope="col" colspan="7">Opstelling</th>
                <th class="border-right subheading" scope="col" colspan="8">LMB</th>
                <th class="border-right subheading" scope="col" colspan="3">LB</th>
                <th class="border-left rotate" scope="col" rowspan="2"><div>Akkoord</div></th>
            </tr>
            <tr>
                <th class="rotate" scope="col"><div>Opstelling</div></th>
                <th class="rotate" scope="col"><div>Hoofdgiek</div></th>
                <th class="rotate" scope="col"><div>Hulpgiek / aanbouwdeel</div></th>
                <th class="rotate" scope="col"><div>Totaal</div></th>
                <th class="rotate" scope="col"><div>Giekhoek hoofdgiek</div></th>
                <th class="rotate" scope="col"><div>Giekhoek hulpgiek</div></th>
                <th class="border-right rotate" scope="col"><div>Aantal parten hijskabel</div></th>
                <th class="rotate" scope="col"><div>Zwenkhoek</div></th>
                <th class="rotate" scope="col"><div>LMB code</div></th>
                <th class="rotate" scope="col"><div>Massa contraballast</div></th>
                <th class="rotate" scope="col"><div>Proeflast</div></th>
                <th class="rotate" scope="col"><div>Toelaatbare vlucht bij proeflast</div></th>
                <th class="rotate" scope="col"><div>LMB treedt in werking bij</div></th>
                <th class="rotate" scope="col"><div>Toelaatbare bedrijfslast bij kolom 13</div></th>
                <th class="border-right rotate" scope="col"><div>Afwijking (11−14) / 14 × 100</div></th>
                <th class="rotate" scope="col"><div>LB treedt in werking</div></th>
                <th class="rotate" scope="col"><div>Toelaatbare bedrijfslast bij kolom 16</div></th>
                <th class="border-right rotate" scope="col"><div>Afwijking (16−17) / 17 × 100</div></th>
            </tr>
            <tr>
                <th scope="col"></th>
                <th scope="col">m</th>
                <th scope="col">#</th>
                <th scope="col">m</th>
                <th scope="col">°</th>
                <th scope="col">°</th>
                <th class="border-right" scope="col">#</th>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col">t</th>
                <th scope="col">t/kg</th>
                <th scope="col">m</th>
                <th scope="col">m</th>
                <th scope="col">t</th>
                <th class="border-right" scope="col">%</th>
                <th scope="col">t</th>
                <th scope="col">t</th>
                <th class="border-right" scope="col">%</th>
                <th scope="col"></th>
            </tr>
            <tr>
                <th scope="col">1</th>
                <th scope="col">2</th>
                <th scope="col">3</th>
                <th scope="col">4</th>
                <th scope="col">5</th>
                <th scope="col">6</th>
                <th class="border-right" scope="col">7</th>
                <th scope="col">8</th>
                <th scope="col">9</th>
                <th scope="col">10</th>
                <th scope="col">11</th>
                <th scope="col">12</th>
                <th scope="col">13</th>
                <th scope="col">14</th>
                <th class="border-right" scope="col">15</th>
                <th scope="col">16</th>
                <th scope="col">17</th>
                <th class="border-right" scope="col">18</th>
                <th scope="col">19</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $i => $row)
                @php
                    $deviations = $testMatrix->deviations($row);
                    $approvals = $testMatrix->approvals($row);
                    $isApproved = $testMatrix->isApproved($row);
                @endphp
                <tr>
                    <th class="border-right" scope="row">{{ $i + 1 }}</th>
                    <td>{{ $row['setup'] }}</td>{{-- 1 --}}
                    <td>{{ $row['main_boom_length'] }}</td>{{-- 2 --}}
                    <td>{{ $row['jib_length'] }}</td>{{-- 3 --}}
                    <td>{{ $row['total_length'] }}</td>{{-- 4 --}}
                    <td>{{ $row['main_boom_angle'] }}</td>{{-- 5 --}}
                    <td>{{ $row['jib_angle'] }}</td>{{-- 6 --}}
                    <td class="border-right">{{ $row['hoist_rope_falls'] }}</td>{{-- 7 --}}
                    <td>{{ $row['slewing_angle'] }}</td>{{-- 8 --}}
                    <td>{{ $row['lmb_code'] }}</td>{{-- 9 --}}
                    <td>{{ $row['counterweight'] }}</td>{{-- 10 --}}
                    <td>{{ $row['test_load'] }}</td>{{-- 11 --}}
                    <td>{{ $row['permissible_radius'] }}</td>{{-- 12 --}}
                    <td>{{ $row['lmb_triggered_at'] }}</td>{{-- 13 --}}
                    <td>{{ $row['lmb_permissible_load'] }}</td>{{-- 14 --}}
                    <td class="border-right {{ $testMatrix->status($approvals['lmb']) }}">{{ $testMatrix->formatDeviation($deviations['lmb']) }}</td>{{-- 15 --}}
                    <td>{{ $row['lb_triggered_at'] }}</td>{{-- 16 --}}
                    <td>{{ $row['lb_permissible_load'] }}</td>{{-- 17 --}}
                    <td class="border-right {{ $testMatrix->status($approvals['lb']) }}">{{ $testMatrix->formatDeviation($deviations['lb']) }}</td>{{-- 18 --}}
                    <td class="result {{ $testMatrix->status($isApproved) }}">{{ $testMatrix->formatApproval($isApproved) }}</td>{{-- 19 --}}
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
