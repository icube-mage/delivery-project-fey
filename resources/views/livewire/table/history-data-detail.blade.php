<div>
    <div class="flex justify-between items-center mb-6">
        <div class="flex justify-between gap-16 w-1/2">
            <div class="w-1/2">
                <div class="flex justify-between items-center border-p px-2 b pb-2 mb-2 w-full">
                    <div class="text-sm font-bold">Create Date</div>
                    <div class="text-sm font-bold">{{ \Carbon\Carbon::parse($catalogPrices[0]->created_at)->format('j M Y H:i')}}</div>
                </div>
                <div class="flex justify-between items-center border-b px-2 pb-2 mb-2 w-full">
                    <div class="text-sm font-bold">User</div>
                    <div class="text-sm font-bold">{{$catalogPrices[0]->user->name}}</div>
                </div>
            </div>
            <div class="w-1/2">
                <div class="flex justify-between items-center border-b px-2 pb-2 mb-2 w-full">
                    <div class="text-sm font-bold">Create Date</div>
                    <div class="text-sm font-bold">{{Str::upper($catalogPrices[0]->brand)}}</div>
                </div>
                <div class="flex justify-between items-center border-b px-2 pb-2 mb-2 w-full">
                    <div class="text-sm font-bold">User</div>
                    <div class="text-sm font-bold">{{Str::upper($catalogPrices[0]->marketplace)}}</div>
                </div>
            </div>
        </div>
        <div>
            <a class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm text-white uppercase bg-emerald-600 hover:bg-emerald-500 active:bg-emerald-500 focus:border-emerald-500 justify-center tracking-widest focus:outline-none focus:ring ring-emerald-300 disabled:opacity-25 transition ease-in-out duration-150" href="{{route('export.catalog.price.hash', $catalogPrices[0]->upload_hash)}}">Download</a>
            <x-input type="text" class="ml-4" placeholder="Search" wire:model="searchTerm" />
        </div>
    </div>
    <x-table>
        <x-thead>
            <tr>
                <x-th>SKU</x-th>
                <x-th>Product Name</x-th>
                <x-th>Price</x-th>
            </tr>
        </x-thead>
        <tbody>
            @foreach($catalogPrices as $catalogPrice)
            <tr class="hover:bg-gray-100">
                <x-td>
                    {{ $catalogPrice->sku }}
                </x-td>
                <x-td>
                    {{ $catalogPrice->name }}
                </x-td>
                <x-td>
                    {{ $catalogPrice->discount_price }}
                </x-td>
            </tr>
            @endforeach
        </tbody>
    </x-table>
    {{ $catalogPrices->links('livewire.pagination') }}
</div>
