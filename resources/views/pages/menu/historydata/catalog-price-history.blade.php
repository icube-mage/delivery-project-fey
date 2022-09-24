<x-app-layout>
    <x-slot name="title">
        <a href="{{route('menu.historicaldata')}}">Historical Log</a> > Detail
    </x-slot>
    
    <x-content-card>
        <livewire:table.history-data-detail hash="{{$hash}}"/>
    </x-content-card>
</x-app-layout>