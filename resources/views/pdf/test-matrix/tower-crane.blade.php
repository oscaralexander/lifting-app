<div class="matrix">
    <h2>@lang('inspections.form.heading_test_matrix')</h2>
    <table>
        <thead>
            <tr>
                <th class="border-bottom border-right rotate" scope="col" rowspan="4"><div>Volgnummer</div></th>
                <th class="border-right heading" scope="col" colspan="8">LMB</th>
                <th class="border-right heading" scope="col" colspan="3">LB</th>
                <th class="rotate" scope="col" rowspan="2"><div>Akkoord</div></th>
            </tr>
            <tr>
                <th class="rotate" scope="col"><div>Aantal parten hijskabel</div></th>
                <th class="border-right rotate" scope="col"><div>LMB code / gang</div></th>
                <th class="rotate" scope="col"><div>Proeflast</div></th>
                <th class="rotate" scope="col"><div>Toelaatbare vlucht bij proeflast</div></th>
                <th class="rotate" scope="col"><div>Katten uit treedt in werking bij</div></th>
                <th class="rotate" scope="col"><div>Hijsen uit treedt in werking bij</div></th>
                <th class="rotate" scope="col"><div>Toelaatbare bedrijfslast bij kolom 5</div></th>
                <th class="border-right rotate" scope="col"><div>Afwijking (3−7) / 7 × 100</div></th>
                <th class="rotate" scope="col"><div>LB treedt in werking</div></th>
                <th class="rotate" scope="col"><div>Toelaatbare bedrijfslast bij kolom 9</div></th>
                <th class="border-right rotate" scope="col"><div>Afwijking (9−10) / 10 × 100</div></th>
            </tr>
            <tr>
                <th scope="col">#</th>
                <th class="border-right" scope="col"></th>
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
                <th class="border-right" scope="col">2</th>
                <th scope="col">3</th>
                <th scope="col">4</th>
                <th scope="col">5</th>
                <th scope="col">6</th>
                <th scope="col">7</th>
                <th class="border-right" scope="col">8</th>
                <th scope="col">9</th>
                <th scope="col">10</th>
                <th class="border-right" scope="col">11</th>
                <th scope="col">12</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $i => $row)
                @php
                    $deviations = $testMatrix->deviations($row);
                    $isApproved = $testMatrix->isApproved($row);
                @endphp
                <tr>
                    <th class="border-right" scope="row">{{ $i + 1 }}</th>
                    <td>{{ $row['hoist_rope_falls'] }}</td>{{-- 1 --}}
                    <td class="border-right">{{ $row['lmb_code'] }}</td>{{-- 2 --}}
                    <td>{{ $row['test_load'] }}</td>{{-- 3 --}}
                    <td>{{ $row['permissible_radius'] }}</td>{{-- 4 --}}
                    <td>{{ $row['lmb_trolley_out_at'] }}</td>{{-- 5 --}}
                    <td>{{ $row['lmb_hoist_up_at'] }}</td>{{-- 6 --}}
                    <td>{{ $row['lmb_permissible_load'] }}</td>{{-- 7 --}}
                    <td class="border-right {{ $testMatrix->deviationStatus($deviations['lmb']) }}">{{ $testMatrix->formatDeviation($deviations['lmb']) }}</td>{{-- 8 --}}
                    <td>{{ $row['lb_triggered_at'] }}</td>{{-- 9 --}}
                    <td>{{ $row['lb_permissible_load'] }}</td>{{-- 10 --}}
                    <td class="border-right {{ $testMatrix->deviationStatus($deviations['lb']) }}">{{ $testMatrix->formatDeviation($deviations['lb']) }}</td>{{-- 11 --}}
                    <td class="result {{ $testMatrix->status($isApproved) }}">{{ $testMatrix->formatApproval($isApproved) }}</td>{{-- 12 --}}
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
