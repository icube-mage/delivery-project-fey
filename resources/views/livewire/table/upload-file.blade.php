<div class="overflow-x-auto relative shadow-md sm:rounded-lg">
    <x-table>
        <x-thead>
            <tr>
                <x-th>
                    <div class="flex">
                        SKU
                    <a href="#" wire:click.prevent="sortBy('sku')" :direction="{{$sortField === 'sku' ? $sortDirection : null}}"><svg xmlns="http://www.w3.org/2000/svg" class="ml-1 w-3 h-3" aria-hidden="true"
                        fill="currentColor" viewBox="0 0 320 512">
                        <path
                            d="M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z" />
                    </svg></a>
                    </div>
                </x-th>
                <x-th>
                    <div class="flex">
                        Product Name
                        <a href="#" wire:click.prevent="sortBy('product_name')" :direction="{{$sortField === 'product_name' ? $sortDirection : null}}"><svg xmlns="http://www.w3.org/2000/svg" class="ml-1 w-3 h-3" aria-hidden="true"
                                fill="currentColor" viewBox="0 0 320 512">
                                <path
                                    d="M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z" />
                            </svg></a>
                    </div>
                </x-th>
                <x-th>
                    <div class="flex">
                        Price
                        <a href="#" wire:click.prevent="sortBy('discount_price')" :direction="{{$sortField === 'discount_price' ? $sortDirection : null}}"><svg xmlns="http://www.w3.org/2000/svg" class="ml-1 w-3 h-3"
                                aria-hidden="true" fill="currentColor" viewBox="0 0 320 512">
                                <path
                                    d="M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z" />
                            </svg></a>
                    </div>
                </x-th>
            </tr>
        </x-thead>
        <tbody class="overflow-y-auto">
            @if ($catalogPriceTemp->isEmpty())
                <tr>
                    <x-td colspan="3" class="text-center text-gray-900">
                        Data not Available
                    </x-td>

                </tr>
            @endif
            @foreach ($catalogPriceTemp as $item)
                <tr>
                    <x-td>
                        {{ $item->sku }}
                    </x-td>
                    <x-td>
                        {{ $item->product_name }}
                    </x-td>
                    <x-td>
                        {{ $item->discount_price }}
                    </x-td>

                </tr>
            @endforeach
        </tbody>
    </x-table>
    <div class="p-4">
        {{ $catalogPriceTemp->links('livewire.pagination') }}
    </div>
</div>
