@use('App\Enums\InspectionObject\BallastConfiguration as InspectionObjectBallastConfiguration')

<x-tabs.tab-panel id="ballast">
    <div class="grid grid--gap-m">
        <div class="grid__col">
            <x-form.select
                default="—"
                :label="__('inspection_object.ballast_configuration.label')"
                wire:model.live="ballast_configuration"
                :options="InspectionObjectBallastConfiguration::options()"
            />
        </div>
        @if ($ballast_configuration === InspectionObjectBallastConfiguration::COUNTERWEIGHT)
            <div class="grid__col l:grid__col--span-6">
                <x-form.input
                    :label="__('inspection_object.counterweight_ballast.label')"
                    model="counterweight_ballast"
                    suffix="kg"
                    type="number"
                />
            </div>
        @endif
        @if ($ballast_configuration === InspectionObjectBallastConfiguration::CENTRAL)
            <div class="grid__col l:grid__col--span-6">
                <x-form.input
                    :label="__('inspection_object.central_ballast.label')"
                    model="central_ballast"
                    suffix="kg"
                    type="number"
                />
            </div>
        @endif
    </div>
</x-tabs.tab-panel>
