<div class="overflow-x-auto relative">
    <div>
        @if (session()->has('message'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert">
                {{ session('message') }}
            </div>
        @elseif (session()->has('error'))
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800"
                role="alert">
                {{ session('error') }}
            </div>
        @endif
    </div>
    <h2 class="text-center text-3xl font-bold mb-6">Price Verification</h2>
    <div class="flex flex-end">
        <button type="button" wire:click="verifyData()"
        class="text-white bg-pink-600 hover:bg-pink-500 focus:ring-4 focus:ring-pink-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-pink-600 dark:hover:bg-pink-500 focus:outline-none dark:focus:ring-pink-800">Verify
        Price</button>
    </div>
    <x-table>
        <x-thead>
            <tr>
                <x-th>
                    Product Id
                </x-th>
                <x-th>
                    Product Name
                </x-th>
                <x-th>
                    Price
                </x-th>
                <x-th>
                    Discount
                </x-th>
                <x-th>
                    Avg Discount
                </x-th>
                <x-th>

                </x-th>
            </tr>
        </x-thead>
        <tbody>
            @forelse ($dataTemp as $index => $item)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <x-td>
                        {{ $item['sku'] }}
                    </x-td>
                    <x-td>
                        {{ $item['product_name'] }}
                    </x-td>
                    <x-td>
                        <input type="text" id="discount_price" wire:model="dataTemp.{{ $index }}.price"
                            wire:change="changePrice({{ $dataTemp[$index]['id'] }}, $event.target.value)"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </x-td>
                    <x-td>
                        {{ $item['discount'] }}
                    </x-td>
                    <x-td>
                        {{ $item['average_discount'] }}
                    </x-td>
                    <x-td class="content-center">
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="dataTemp.{{ $index }}.is_whitelist" value=""
                                wire:change="changeWhitelist({{ $dataTemp[$index]['id'] }}, $event.target.checked)"
                                class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="default-checkbox"
                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Whitelist</label>
                        </div>
                    </x-td>
                </tr>
            @empty
                <tr>
                    <x-td colspan="6" class="text-center text-gray-900">
                        Data not Available
                    </x-td>
                </tr>
            @endforelse
        </tbody>
    </x-table>
    {{-- <div class="p-4">
        {{ $catalogPriceTemp->links('livewire.pagination') }}
    </div> --}}
</div>
