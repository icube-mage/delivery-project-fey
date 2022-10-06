<x-app-layout>
    <x-slot name="title">
        <a href="{{route('menu.uploadfile')}}">Upload File</a> > Check Price
    </x-slot>
    <x-content-card id="main">
        <livewire:table.check-price :dataTemp="$dataCatalog" :brand="$brand" :marketplace="$marketplace" :errorData="$errorData" :errorIds="$errorIds"/>
    </x-content-card>
</x-app-layout>