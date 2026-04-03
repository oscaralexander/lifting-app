<x-tabs.tab-panel id="rail">
    <div class="grid grid--gap-m">
        <div class="grid__col l:grid__col--span-6">
            <x-form.input
                :label="__('inspection_object.rail_track_gauge.label')"
                model="rail_track_gauge"
                suffix="m"
                type="number"
            />
        </div>
        <div class="grid__col l:grid__col--span-6">
            <x-form.input
                :label="__('inspection_object.rail_wheelbase.label')"
                model="rail_wheelbase"
                suffix="m"
                type="number"
            />
        </div>
        <div class="grid__col l:grid__col--span-6">
            <x-form.input
                :label="__('inspection_object.crane_track_length.label')"
                model="crane_track_length"
                suffix="m"
                type="number"
            />
        </div>
    </div>
</x-tabs.tab-panel>
