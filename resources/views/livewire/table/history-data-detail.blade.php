<div>
    <div class="flex justify-between items-center mb-6">
        <div class="flex justify-between gap-16 w-1/2">
            <div class="w-1/2">
                <div class="flex justify-between items-center border-b px-2 b pb-2 mb-2 w-full">
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
                    <div class="text-sm font-bold">Brand</div>
                    <div class="text-sm font-bold">{{Str::upper($catalogPrices[0]->brand)}}</div>
                </div>
                <div class="flex justify-between items-center border-b px-2 pb-2 mb-2 w-full">
                    <div class="text-sm font-bold">User</div>
                    <div class="text-sm font-bold">{{Str::upper($catalogPrices[0]->marketplace)}}</div>
                </div>
            </div>
        </div>
        <div>
            <a class="excel-btn" href="{{route('export.catalog.price.hash', $catalogPrices[0]->upload_hash)}}">Download</a>
            <x-input type="text" class="ml-4" placeholder="Search name or sku" wire:model="searchTerm" />
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
            @if($errorData==false)
            @foreach($catalogPrices as $catalogPrice)
            <tr class="hover:bg-gray-100 first-letter:bg-white border-b">
                <x-td>
                    {{ $catalogPrice->sku }}
                </x-td>
                <x-td>
                    {{ $catalogPrice->product_name }}
                </x-td>
                <x-td>
                    {{ $catalogPrice->discount_price }}
                </x-td>
            </tr>
            @endforeach
            @else
                <tr class="hover:bg-gray-100 first-letter:bg-white border-b">
                    <x-td class="text-center text-gray-900" colspan="3">
                        -- Not Found --
                    </x-td>
                </tr>
            @endif
        </tbody>
    </x-table>
    @if($errorData==false)
    <div class="py-4">
        {{ $catalogPrices->links('livewire.pagination') }}
    </div>
    @endif
</div>
