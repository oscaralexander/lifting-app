<table>
    <thead>
        <tr>
            <th class="border-bottom border-right rotate" scope="col" rowspan="5">Volgnummer</th>
            <th class="border-right heading" scope="col" colspan="8">Gegevens volgens hijstabel</th>
            <th class="heading" scope="col" colspan="12">Beproeving</th>
        </tr>
        <tr>
            <th class="border-right subheading" scope="col" colspan="8">Opstelling</th>
            <th class="border-right subheading" scope="col" colspan="8">LMB</th>
            <th class="border-right subheading" scope="col" colspan="3">LB</th>
            <th class="rotate" scope="col" rowspan="2">Akkoord</th>
        </tr>
        <tr>
            <th class="rotate" scope="col">Opstelling</th>
            <th class="rotate" scope="col">Hoofd / knikgiek</th>
            <th class="rotate" scope="col">Mech. deel knikgiek (in/uit)</th>
            <th class="rotate" scope="col">Fly jib</th>
            <th class="rotate" scope="col">Mech. deel fly-jib (in/uit)</th>
            <th class="rotate" scope="col">Hoofd / knikgiek</th>
            <th class="rotate" scope="col">Fly jib</th>
            <th class="border-right rotate" scope="col">Aantal parten hijskabel</th>
            <th class="rotate" scope="col">Zwenkhoek</th>
            <th class="rotate" scope="col">Massa contraballast / schoteldruk</th>
            <th class="rotate" scope="col">Proeflast</th>
            <th class="rotate" scope="col">Toelaatbare vlucht bij proeflast</th>
            <th class="rotate" scope="col">LMB treedt in werking bij</th>
            <th class="rotate" scope="col">Toelaatbare bedrijfslast bij kolom 13</th>
            <th class="rotate" scope="col">Max. toelaatbare afwijking / Δ ≤ 8+0.5R = ≤ 20</th>
            <th class="border-right rotate" scope="col">Afwijking (11−14) / 14 × 100</th>
            <th class="rotate" scope="col">LB treedt in werking</th>
            <th class="rotate" scope="col">Toelaatbare bedrijfslast bij kolom 17</th>
            <th class="border-right rotate" scope="col">Afwijking (17−18) / 18 × 100</th>
        </tr>
        <tr>
            <th scope="col"></th>
            <th scope="col">m</th>
            <th scope="col"></th>
            <th scope="col">m</th>
            <th scope="col"></th>
            <th scope="col">°</th>
            <th scope="col">°</th>
            <th class="border-right" scope="col">#</th>
            <th scope="col"></th>
            <th scope="col">t</th>
            <th scope="col">t</th>
            <th scope="col">m</th>
            <th scope="col">m</th>
            <th scope="col">t</th>
            <th scope="col">%</th>
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
            <th scope="col">7</th>
            <th class="border-right" scope="col">8</th>
            <th scope="col">9</th>
            <th scope="col">10</th>
            <th scope="col">11</th>
            <th scope="col">12</th>
            <th scope="col">13</th>
            <th scope="col">14</th>
            <th scope="col">15</th>
            <th class="border-right" scope="col">16</th>
            <th scope="col">17</th>
            <th scope="col">18</th>
            <th class="border-right" scope="col">19</th>
            <th scope="col">20</th>
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
                <td>
                    <select wire:model="rows.{{ $i }}.setup">
                        <option value="">Kies</option>
                        @foreach ($this->testMatrix::SETUP_OPTIONS as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                </td>{{-- 1 --}}
                <td><input type="text" wire:model="rows.{{ $i }}.main_boom_length" /></td>{{-- 2 --}}
                <td><input type="text" wire:model="rows.{{ $i }}.knuckle_boom_extension" /></td>{{-- 3 --}}
                <td><input type="text" wire:model="rows.{{ $i }}.fly_jib_length" /></td>{{-- 4 --}}
                <td><input type="text" wire:model="rows.{{ $i }}.fly_jib_extension" /></td>{{-- 5 --}}
                <td><input type="text" wire:model="rows.{{ $i }}.main_boom_angle" /></td>{{-- 6 --}}
                <td><input type="text" wire:model="rows.{{ $i }}.fly_jib_angle" /></td>{{-- 7 --}}
                <td class="border-right"><input type="text" wire:model="rows.{{ $i }}.hoist_rope_falls" /></td>{{-- 8 --}}
                <td><input type="text" wire:model="rows.{{ $i }}.slewing_angle" /></td>{{-- 9 --}}
                <td><input type="text" wire:model="rows.{{ $i }}.counterweight" /></td>{{-- 10 --}}
                <td><input type="text" wire:model.live.blur="rows.{{ $i }}.test_load" /></td>{{-- 11 --}}
                <td><input type="text" wire:model="rows.{{ $i }}.permissible_radius" /></td>{{-- 12 --}}
                <td><input type="text" wire:model="rows.{{ $i }}.lmb_triggered_at" /></td>{{-- 13 --}}
                <td><input type="text" wire:model.live.blur="rows.{{ $i }}.lmb_permissible_load" /></td>{{-- 14 --}}
                <td><input type="text" wire:model.live.blur="rows.{{ $i }}.lmb_max_deviation" /></td>{{-- 15 --}}
                <td class="border-right {{ $this->testMatrix->status($approvals['lmb']) }}">{{ $this->testMatrix->formatDeviation($deviations['lmb']) }}</td>{{-- 16 --}}
                <td><input type="text" wire:model.live.blur="rows.{{ $i }}.lb_triggered_at" /></td>{{-- 17 --}}
                <td><input type="text" wire:model.live.blur="rows.{{ $i }}.lb_permissible_load" /></td>{{-- 18 --}}
                <td class="border-right {{ $this->testMatrix->status($approvals['lb']) }}">{{ $this->testMatrix->formatDeviation($deviations['lb']) }}</td>{{-- 19 --}}
                <td class="result {{ $this->testMatrix->status($isApproved) }}">{{ $this->testMatrix->formatApproval($isApproved) }}</td>{{-- 20 --}}
            </tr>
        @endforeach
    </tbody>
</table>
