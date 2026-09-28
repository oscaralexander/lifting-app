<table>
    <thead>
        <tr>
            <th class="border-bottom border-right rotate" scope="col" rowspan="4">Volgnummer</th>
            <th class="border-right heading" scope="col" colspan="8">LMB</th>
            <th class="border-right heading" scope="col" colspan="3">LB</th>
            <th class="rotate" scope="col" rowspan="2">Akkoord</th>
        </tr>
        <tr>
            <th class="rotate" scope="col">Aantal parten hijskabel</th>
            <th class="border-right rotate" scope="col">LMB code / gang</th>
            <th class="rotate" scope="col">Proeflast</th>
            <th class="rotate" scope="col">Toelaatbare vlucht bij proeflast</th>
            <th class="rotate" scope="col">Katten uit treedt in werking bij</th>
            <th class="rotate" scope="col">Hijsen uit treedt in werking bij</th>
            <th class="rotate" scope="col">Toelaatbare bedrijfslast bij kolom 5</th>
            <th class="border-right rotate" scope="col">Afwijking (3−7) / 7 × 100</th>
            <th class="rotate" scope="col">LB treedt in werking</th>
            <th class="rotate" scope="col">Toelaatbare bedrijfslast bij kolom 9</th>
            <th class="border-right rotate" scope="col">Afwijking (9−10) / 10 × 100</th>
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
                $deviations = $this->testMatrix->deviations($row);
                $approvals = $this->testMatrix->approvals($row);
                $isApproved = $this->testMatrix->isApproved($row);
            @endphp
            <tr wire:key="matrix-row-{{ $i }}">
                <th class="border-right" scope="row">{{ $i + 1 }}</th>
                <td><input type="text" wire:model="rows.{{ $i }}.hoist_rope_falls" /></td>{{-- 1 --}}
                <td class="border-right"><input type="text" wire:model="rows.{{ $i }}.lmb_code" /></td>{{-- 2 --}}
                <td><input type="text" wire:model.live.blur="rows.{{ $i }}.test_load" /></td>{{-- 3 --}}
                <td><input type="text" wire:model="rows.{{ $i }}.permissible_radius" /></td>{{-- 4 --}}
                <td><input type="text" wire:model="rows.{{ $i }}.lmb_trolley_out_at" /></td>{{-- 5 --}}
                <td><input type="text" wire:model="rows.{{ $i }}.lmb_hoist_up_at" /></td>{{-- 6 --}}
                <td><input type="text" wire:model.live.blur="rows.{{ $i }}.lmb_permissible_load" /></td>{{-- 7 --}}
                <td class="border-right {{ $this->testMatrix->status($approvals['lmb']) }}">{{ $this->testMatrix->formatDeviation($deviations['lmb']) }}</td>{{-- 8 --}}
                <td><input type="text" wire:model.live.blur="rows.{{ $i }}.lb_triggered_at" /></td>{{-- 9 --}}
                <td><input type="text" wire:model.live.blur="rows.{{ $i }}.lb_permissible_load" /></td>{{-- 10 --}}
                <td class="border-right {{ $this->testMatrix->status($approvals['lb']) }}">{{ $this->testMatrix->formatDeviation($deviations['lb']) }}</td>{{-- 11 --}}
                <td class="result {{ $this->testMatrix->status($isApproved) }}">{{ $this->testMatrix->formatApproval($isApproved) }}</td>{{-- 12 --}}
            </tr>
        @endforeach
    </tbody>
</table>
