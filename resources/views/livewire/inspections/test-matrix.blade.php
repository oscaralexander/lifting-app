@use('App\Enums\InspectionObject\Crane\BaseConfiguration')

<div class="matrix">
    <table>
        <thead>
            <tr>
                <th class="border-bottom border-right rotate" scope="col" rowspan="4">Volgnummer beproeving</th>
                <th class="border-right heading" scope="col" colspan="15">Gegevens volgens hijstabel</th>
                <th class="heading" scope="col" colspan="8">Beproeving</th>
            </tr>
            <tr>
                {{-- Gegevens volgens hijstabel --}}
                <th class="border-right subheading" scope="col" colspan="4">Opstelling</th>
                <th class="border-right subheading" scope="col" colspan="3">Giek</th>
                <th class="border-right subheading" scope="col" colspan="2">Ballast</th>
                <th class="rotate" scope="col" rowspan="2">Aantal parten hijskabel</th>
                <th scope="col">Zwenk&shy;hoek</th>
                <th class="rotate" scope="col" rowspan="2">LMB code</th>
                <th class="rotate" scope="col" rowspan="2">Gang (snelheid)</th>
                <th class="rotate" scope="col" rowspan="2">Toelaatbare bedrijfslast<br>kolom 17</th>
                <th class="border-right rotate" scope="col" rowspan="2">Toelaatbare bedrijfslast<br>kolom 15 (max. vlucht)</th>
                {{-- Beproeving --}}
                <th class="border-right" scope="col" colspan="4">LMB</th>
                <th class="border-right" scope="col" colspan="2">LB</th>
                <th scope="col" colspan="2">Akkoord</th>
            </tr>
            <tr>
                <th class="rotate" scope="col">Aan fundatie</th>
                <th class="rotate" scope="col">Op kruisframe</th>
                <th class="rotate" scope="col">Op rails</th>
                <th class="border-right rotate" scope="col">Op stempels</th>
                {{-- Giek --}}
                <th class="rotate" scope="col">Gieklengte (m)</th>
                <th class="rotate" scope="col">Giekhoek (º)</th>
                <th class="border-right rotate" scope="col">Haakhoogte (m)</th>
                {{-- Ballast --}}
                <th class="rotate" scope="col">Eigen massa centraal ballast (t/kg)</th>
                <th class="border-right rotate" scope="col">Eigen massa contraballast (t/kg)</th>
                <th class="rotate" scope="col">R = 360º, A = Achter<br>Z = Zij, V = Voor</th>
                <th class="rotate" scope="col">Proeflast (t/kg)</th>
                <th class="rotate" scope="col">LMB treed in werking bij<br>katten uit (m)</th>
                <th class="rotate" scope="col">LMB treed in werking bij<br>hijsen (m)</th>
                <th class="border-right rotate" scope="col">Afwijking LMB</th>
                <th class="rotate" scope="col">LB treedt in werking bij (t/kg)</th>
                <th class="border-right rotate" scope="col">Afwijking LB</th>
                <th scope="col">Ja</th>
                <th scope="col">Nee</th>
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
                <th scope="col">12</th>
                <th scope="col">13</th>
                <th class="border-right" scope="col">14</th>
                <th scope="col">15</th>
                <th colspan="2" scope="col">16</th>
                <th scope="col">17</th>
                <th scope="col">18</th>
                <th scope="col">19</th>
                <th class="border-right" scope="col">20</th>
                <th scope="col">21</th>
                <th scope="col">22</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 0; $i < 10; $i++)
                <tr
                    wire:key="matrix-row-{{ $i }}"
                    x-data="{
                        get col_16() {
                            if (!$wire.matrix[this.row].col_13 || !$wire.matrix[this.row].col_14) {
                                return null;
                            }

                            return (($wire.matrix[this.row].col_14 - $wire.matrix[this.row].col_13) / $wire.matrix[this.row].col_13) * 100;
                        },
                        row: {{ $i }},
                    }"
                >
                    <th class="border-right" scope="row">{{ $i + 1 }}</th>
                    <td>
                        @if ($this->inspection->inspectable?->base_configuration === BaseConfiguration::FOUNDATION_ANCHORS)
                            <x-icon icon="check" />
                        @endif
                    </td>{{-- 1 --}}
                    <td>
                        @if ($this->inspection->inspectable?->base_configuration === BaseConfiguration::CROSS_FRAME)
                            <x-icon icon="check" />
                        @endif
                    </td>{{-- 2 --}}
                    <td>
                        @if ($this->inspection->inspectable?->base_configuration === BaseConfiguration::RAIL_TRAVELLING)
                            <x-icon icon="check" />
                        @endif
                    </td>{{-- 3 --}}
                    <td class="border-right">
                        @if ($this->inspection->inspectable?->outrigger_type)
                            <x-icon icon="check" />
                        @endif
                    </td>{{-- 4 --}}
                    <td>{{ $this->inspection->inspectable?->boom_length ?? '' }}</td>{{-- 5 --}}
                    <td><input type="text" wire:model="matrix.{{ $i }}.col_5" /></td>{{-- 6 --}}
                    <td class="border-right">{{ $this->inspection->inspectable?->hook_height ?? '' }}</td>{{-- 7 --}}
                    <td>{{ $this->inspection->inspectable?->central_ballast ?? '' }}</td>{{-- 8 --}}
                    <td class="border-right">{{ $this->inspection->inspectable?->counter_ballast ?? '' }}</td>{{-- 9 --}}
                    <td><input type="text" wire:model="matrix.{{ $i }}.col_8" /></td>{{-- 8 --}}
                    <td><input type="text" wire:model="matrix.{{ $i }}.col_9" /></td>{{-- 9 --}}
                    <td><input type="text" wire:model="matrix.{{ $i }}.col_10" /></td>{{-- 10 --}}
                    <td><input type="text" wire:model="matrix.{{ $i }}.col_11" /></td>{{-- 11 --}}
                    <td><input type="text" wire:model="matrix.{{ $i }}.col_12" /></td>{{-- 12 --}}
                    <td class="border-right"><input type="text" wire:model="matrix.{{ $i }}.col_13" /></td>{{-- 13 --}}
                    <td><input type="text" wire:model="matrix.{{ $i }}.col_14" /></td>{{-- 14 --}}
                    <td><input type="text" wire:model="matrix.{{ $i }}.col_15_1" /></td>{{-- 15.1 --}}
                    <td><input type="text" wire:model="matrix.{{ $i }}.col_15_2" /></td>{{-- 15.2 --}}
                    <td><input readonly type="text" x-model="col_16" /></td>{{-- 16 --}}
                    <td><input type="text" wire:model="matrix.{{ $i }}.col_20" /></td>{{-- 20 --}}
                    <td class="border-right"><input readonly type="text" wire:model="matrix.{{ $i }}.col_20" /></td>{{-- 20 --}}
                    <td></td>{{-- 21 --}}
                    <td></td>{{-- 22 --}}
                </tr>
            @endfor
        </tbody>
    </table>
</div>
