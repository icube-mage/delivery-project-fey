<div>
    <div class="flex justify-between items-center mb-6">
        <a class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm text-white uppercase bg-emerald-600 hover:bg-emerald-500 active:bg-emerald-500 focus:border-emerald-500 justify-center tracking-widest focus:outline-none focus:ring ring-emerald-300 disabled:opacity-25 transition ease-in-out duration-150" href="{{route('export.catalog.price')}}">Download</a>
        <x-input type="text" placeholder="Search" wire:model="searchTerm" />
    </div>
    <x-table>
        <x-thead>
            <tr>
                <x-th>Upload ID</x-th>
                <x-th>Create Date</x-th>
                <x-th>User</x-th>
                <x-th>Brand</x-th>
                <x-th>Marketplace</x-th>
            </tr>
        </x-thead>
        <tbody>
            @foreach($catalogPrices as $catalogPrice)
            <tr class="hover:bg-gray-100">
                <x-th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <a href="{{ route('menu.historicaldata.show', $catalogPrice->upload_hash) }}" class="text-sky-600 hover:text-sky-700">{{ $catalogPrice->upload_hash }}</a>
                </x-th>
                <x-td>
                    {{ $catalogPrice->start_date }}
                </x-td>
                <x-td>
                    {{ $catalogPrice->name }}
                </x-td>
                <x-td>
                    {{ $catalogPrice->brand }}
                </x-td>
                <x-td>
                    {{ $catalogPrice->marketplace }}
                </x-td>
            </tr>
            @endforeach
        </tbody>
    </x-table>
    {{ $catalogPrices->links('livewire.pagination') }}
</div>
