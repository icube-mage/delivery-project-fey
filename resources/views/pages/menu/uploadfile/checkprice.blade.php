<x-app-layout>
    <x-slot name="title">
        <a href="{{route('menu.uploadfile')}}">Upload File</a> > Check Price
    </x-slot>
    <x-content-card id="main">
        <livewire:table.check-price/>
    </x-content-card>
</x-app-layout>