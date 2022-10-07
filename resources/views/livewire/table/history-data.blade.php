<div>
    <div class="flex justify-between items-center mb-6">
        <form action="{{route('export.catalog.price')}}" method="POST">
            @csrf
            <input type="hidden" name="filter" value="{{$searchTerm}}"/>
            <button type="submit" class="excel-btn">Download</button>
        </form>
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
            @forelse($catalogPrices as $catalogPrice)
            <tr class="hover:bg-gray-100 first-letter:bg-white border-b">
                <x-th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                    <a href="{{ route('menu.historicaldata.show', $catalogPrice->upload_hash) }}" class="text-sky-600 hover:text-sky-700">
                        {{ explode("-",$catalogPrice->upload_hash)[0] }}
                    </a>
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
            @empty
            <tr>
                <x-th scope="row" colspan="6" class="text-center text-gray-900">
                    -- Nothing User Log Data --
                </x-th>
            </tr>
            @endforelse
        </tbody>
    </x-table>
    <div class="py-4">
        {{ $catalogPrices->links('livewire.pagination') }}
    </div>
</div>
