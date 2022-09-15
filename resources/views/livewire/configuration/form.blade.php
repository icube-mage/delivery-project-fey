<div>
    <form class="w-1/3">
        <div class="flex items-center justify-between gap-6 py-4 border-b">
            <x-label>CSV Field Separator</x-label>
            <x-input type="text" wire:model="csv_separator"/>
        </div>
        <div class="flex items-center justify-between gap-6 py-4 border-b">
            <x-label>Max time to keep record</x-label>
            <x-input type="number" wire:model="time_calculate"/>
        </div>
        <div class="flex items-center justify-between gap-6 py-4">
            <x-label>Cron Schedule</x-label>
            <x-input type="text" wire:model="cron_schedule"/>
        </div>
    </form>
</div>
