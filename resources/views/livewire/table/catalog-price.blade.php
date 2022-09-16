<div>
    <div>
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
            <tr>
                <x-th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $catalogPrice->upload_hash }}
                </x-th>
                <x-td>
                    {{ \Carbon\Carbon::parse($user->created_at)->format('j F, Y H:i:s') }}
                </x-td>
                <x-td>
                    {{ $catalogPrice->user->name }}
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
    {{ $users->links('livewire.pagination') }}
</div>
