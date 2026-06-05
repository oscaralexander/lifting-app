@use('App\Enums\InspectionObject\Crane\BaseConfiguration')

<div class="matrix u-stack u-stack-gap-m">
    <h2>@lang('inspections.form.heading_test_matrix')</h2>
    <table>
        <thead>
            <tr>
                <th class="border-bottom border-right rotate" scope="col" rowspan="4">Volgnummer beproeving</th>
                <th class="border-right heading" scope="col" colspan="12">Gegevens volgens hijstabel</th>
                <th class="heading" scope="col" colspan="10">Beproeving</th>
            </tr>
            <tr>
                {{-- Gegevens volgens hijstabel --}}
                <th class="border-right subheading" scope="col" colspan="4">Opstelling</th>
                <th class="border-right subheading" scope="col" colspan="3">Giek</th>
                <th class="border-right subheading" scope="col" colspan="2">Ballast</th>
                <th class="border-left rotate" scope="col" rowspan="2">Aantal parten hijskabel</th>
                <th scope="col">Zwenk&shy;hoek</th>
                <th class="border-right rotate" scope="col" rowspan="2">LMB Code / Gang</th>
                {{-- Beproeving --}}
                <th class="border-right" scope="col" colspan="6">LMB</th>
                <th class="border-right" scope="col" colspan="3">LB</th>
                <th class="border-left rotate" scope="col" rowspan="2">Akkoord</th>
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
                <th class="rotate" scope="col">Massa centraal ballast (t/kg)</th>
                <th class="border-right rotate" scope="col">Massa contraballast (t/kg)</th>
                <th class="rotate" scope="col">R = 360º, A = Achter<br>Z = Zij, V = Voor</th>
                {{-- LMB --}}
                <th class="rotate" scope="col">Proeflast (t/kg)</th>
                <th class="rotate" scope="col">Toelaatbare vlucht bij proeflast</th>
                <th class="rotate" scope="col">LMB treed in werking<br>bij katten uit (m)</th>
                <th class="rotate" scope="col">LMB treed in werking<br>bij hijsen (m)</th>
                <th class="rotate" scope="col">Toelaatbare bedrijfslast<br>bij kolom 18</th>
                <th class="border-right rotate" scope="col">Afwijking LMB</th>
                {{-- LB --}}
                <th class="rotate" scope="col">LB treedt in werking bij (t/kg)</th>
                <th class="rotate" scope="col">Toelaatbare bedrijfslast<br>bij kolom 20</th>
                <th class="border-right rotate" scope="col">Afwijking LB</th>
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
                <tr
                    wire:key="matrix-row-{{ $i }}"
                    x-data="{
                        col_17: '—',
                        col_20: '—',
                        get col_21() {
                            const col_17 = parseFloat(this.col_17);
                            const col_20 = parseFloat(this.col_20);

                            if (isNaN(col_17) && isNaN(col_20)) {
                                return '—';
                            }

                            if (col_17 >= 10 || col_20 >= 10) {
                                return 'NEE';
                            }

                            return 'JA';
                        },
                        init() {
                            this.updateCol17();
                            this.updateCol20();
                        },
                        row: {{ $i }},
                        updateCol17() {
                            if (!$wire.matrix[this.row].col_14 || !$wire.matrix[this.row].col_16) {
                                this.col_17 = '—';
                                return;
                            }

                            const col_14 = parseFloat($wire.matrix[this.row].col_14.replace(',', '.'));
                            const col_16 = parseFloat($wire.matrix[this.row].col_16.replace(',', '.'));

                            this.col_17 = (((col_16 - col_14) / col_14) * 100).toFixed(2) + '%';
                        },
                        updateCol20() {
                            if (!$wire.matrix[this.row].col_15 || !$wire.matrix[this.row].col_19) {
                                this.col_20 = '—';
                                return;
                            }

                            const col_15 = parseFloat($wire.matrix[this.row].col_15.replace(',', '.'));
                            const col_19 = parseFloat($wire.matrix[this.row].col_19.replace(',', '.'));

                            this.col_20 = (((col_19 - col_15) / col_15) * 100).toFixed(2) + '%';
                        },
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
                    <td>{{ $this->inspection->inspectable?->boom_length ? format_number($this->inspection->inspectable?->boom_length) : '' }}</td>{{-- 5 --}}
                    <td><input type="text" wire:model="matrix.{{ $i }}.col_6" /></td>{{-- 6 --}}
                    <td class="border-right">{{ $this->inspection->inspectable?->hook_height ? format_number($this->inspection->inspectable->hook_height) : '' }}</td>{{-- 7 --}}
                    <td>{{ $this->inspection->inspectable?->central_ballast ? format_number($this->inspection->inspectable->central_ballast) : '' }}</td>{{-- 8 --}}
                    <td class="border-right">{{ $this->inspection->inspectable?->counter_ballast ? format_number($this->inspection->inspectable->counter_ballast) : '' }}</td>{{-- 9 --}}
                    <td><input type="text" wire:model="matrix.{{ $i }}.col_10" /></td>{{-- 10 --}}
                    <td><input type="text" wire:model="matrix.{{ $i }}.col_11" /></td>{{-- 11 --}}
                    <td class="border-right"><input type="text" wire:model="matrix.{{ $i }}.col_12" /></td>{{-- 12 (LMB Code / Gang) --}}
                    <td><input type="text" wire:model="matrix.{{ $i }}.col_16" x-on:blur="updateCol17()" /></td>{{-- 13 (Proeflast) --}}
                    <td><input type="text" wire:model="matrix.{{ $i }}.col_18" /></td>{{-- 14 (Toelaatbare vlucht bij proeflast) --}}
                    <td><input type="text" wire:model="matrix.{{ $i }}.col_17_1" /></td>{{-- 15.1 --}}
                    <td><input type="text" wire:model="matrix.{{ $i }}.col_17_2" /></td>{{-- 15.2 --}}
                    <td><input type="text" wire:model="matrix.{{ $i }}.col_14" x-on:blur="updateCol17()" /></td>{{-- 16 (Toelaatbare bedrijfslast bij kolom 18) --}}
                    <td
                        class="border-right"
                        :class="{
                            'failed': parseFloat(col_17) >= 10,
                            'neutral': col_17 === '—',
                            'passed': parseFloat(col_17) < 10,
                        }"
                        x-text="col_17.replace('.', ',')"
                    ></td>{{-- 17 (Afwijking LMB) --}}
                    <td><input type="text" wire:model="matrix.{{ $i }}.col_19" x-on:blur="updateCol20()" /></td>{{-- 18 (LB treedt in werking bij) --}}
                    <td><input type="text" wire:model="matrix.{{ $i }}.col_15" x-on:blur="updateCol20()" /></td>{{-- 19 (Toelaatbare bedrijfslast bij kolom 20) --}}
                    <td
                        class="border-right"
                        :class="{
                            'failed': parseFloat(col_20) >= 10,
                            'neutral': col_20 === '—',
                            'passed': parseFloat(col_20) < 10,
                        }"
                        x-text="col_20.replace('.', ',')"
                    ></td>{{-- 20 (Afwijking LB) --}}
                    <td
                        class="result"
                        :class="{
                            'failed': col_21 === 'NEE',
                            'neutral': col_21 === '—',
                            'passed': col_21 === 'JA',
                        }"
                        x-text="col_21"
                    >
                    </td>{{-- 21 (Akkoord) --}}
                </tr>
            @endfor
        </tbody>
    </table>
</div>
