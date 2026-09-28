<div class="matrix">
    <h2>@lang('inspections.form.heading_test_matrix')</h2>
    <table>
        <thead>
            <tr>
                <th class="border-bottom border-right rotate" scope="col" rowspan="5"><div>Volgnummer</div></th>
                <th class="border-right heading" scope="col" colspan="5">Gegevens volgens hijstabel</th>
                <th class="heading" scope="col" colspan="12">Beproeving</th>
            </tr>
            <tr>
                <th class="border-right subheading" scope="col" colspan="5">Opstelling</th>
                <th class="border-right subheading" scope="col" colspan="8">LMB</th>
                <th class="border-right subheading" scope="col" colspan="3">LB</th>
                <th class="rotate" scope="col" rowspan="2"><div>Akkoord</div></th>
            </tr>
            <tr>
                <th class="rotate" scope="col"><div>Opstelling</div></th>
                <th class="rotate" scope="col"><div>Gieklengte</div></th>
                <th class="rotate" scope="col"><div>Haakhoogte</div></th>
                <th class="rotate" scope="col"><div>Eigen gewicht contraballast</div></th>
                <th class="border-right rotate" scope="col"><div>Aantal parten hijskabel</div></th>
                <th class="rotate" scope="col"><div>Zwenkhoek</div></th>
                <th class="rotate" scope="col"><div>LMB code / gang</div></th>
                <th class="rotate" scope="col"><div>Proeflast</div></th>
                <th class="rotate" scope="col"><div>Toelaatbare vlucht bij proeflast</div></th>
                <th class="rotate" scope="col"><div>Katten uit treedt in werking bij</div></th>
                <th class="rotate" scope="col"><div>Hijsen uit treedt in werking bij</div></th>
                <th class="rotate" scope="col"><div>Toelaatbare bedrijfslast bij kolom 11</div></th>
                <th class="border-right rotate" scope="col"><div>Afwijking (8−12) / 12 × 100</div></th>
                <th class="rotate" scope="col"><div>LB treedt in werking</div></th>
                <th class="rotate" scope="col"><div>Toelaatbare bedrijfslast bij kolom 14</div></th>
                <th class="border-right rotate" scope="col"><div>Afwijking (14−15) / 15 × 100</div></th>
            </tr>
            <tr>
                <th scope="col"></th>
                <th scope="col">m</th>
                <th scope="col">m</th>
                <th scope="col">t</th>
                <th class="border-right" scope="col">#</th>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col">t</th>
                <th scope="col">m</th>
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
                <th class="border-right" scope="col">5</th>
                <th scope="col">6</th>
                <th scope="col">7</th>
                <th scope="col">8</th>
                <th scope="col">9</th>
                <th scope="col">10</th>
                <th scope="col">11</th>
                <th scope="col">12</th>
                <th class="border-right" scope="col">13</th>
                <th scope="col">14</th>
                <th scope="col">15</th>
                <th class="border-right" scope="col">16</th>
                <th scope="col">17</th>
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
                    <td>{{ $row['boom_length'] }}</td>{{-- 2 --}}
                    <td>{{ $row['hook_height'] }}</td>{{-- 3 --}}
                    <td>{{ $row['counterweight'] }}</td>{{-- 4 --}}
                    <td class="border-right">{{ $row['hoist_rope_falls'] }}</td>{{-- 5 --}}
                    <td>{{ $row['slewing_angle'] }}</td>{{-- 6 --}}
                    <td>{{ $row['lmb_code'] }}</td>{{-- 7 --}}
                    <td>{{ $row['test_load'] }}</td>{{-- 8 --}}
                    <td>{{ $row['permissible_radius'] }}</td>{{-- 9 --}}
                    <td>{{ $row['lmb_trolley_out_at'] }}</td>{{-- 10 --}}
                    <td>{{ $row['lmb_hoist_up_at'] }}</td>{{-- 11 --}}
                    <td>{{ $row['lmb_permissible_load'] }}</td>{{-- 12 --}}
                    <td class="border-right {{ $testMatrix->status($approvals['lmb']) }}">{{ $testMatrix->formatDeviation($deviations['lmb']) }}</td>{{-- 13 --}}
                    <td>{{ $row['lb_triggered_at'] }}</td>{{-- 14 --}}
                    <td>{{ $row['lb_permissible_load'] }}</td>{{-- 15 --}}
                    <td class="border-right {{ $testMatrix->status($approvals['lb']) }}">{{ $testMatrix->formatDeviation($deviations['lb']) }}</td>{{-- 16 --}}
                    <td class="result {{ $testMatrix->status($isApproved) }}">{{ $testMatrix->formatApproval($isApproved) }}</td>{{-- 17 --}}
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
