<div class="overflow-x-auto relative w-full">
    <div>
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-x-0"
                x-transition:leave-end="opacity-0 transform -translate-x-40"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                class="text-green-700 px-6 py-4 border-0 rounded relative mb-4 bg-green-100">
                <span class="inline-block align-middle mr-8">
                    {{ session('message') }}
                </span>
                <button @click="show = false" class="absolute bg-transparent text-2xl font-semibold leading-none right-0 top-0 mt-4 mr-6 outline-none focus:outline-none">
                    <span>×</span>
                </button>
            </div>
        @elseif (session()->has('error'))
            <div x-data="{ show: true }" x-show="show"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-x-0"
                x-transition:leave-end="opacity-0 transform -translate-x-40"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                class="text-rose-700 px-6 py-4 border-0 rounded relative mb-4 bg-rose-100">
                <span class="inline-block align-middle mr-8">
                    {{ session('error') }}
                </span>
                <button @click="show = false" class="absolute bg-transparent text-2xl font-semibold leading-none right-0 top-0 mt-4 mr-6 outline-none focus:outline-none">
                    <span>×</span>
                </button>
            </div>
        @endif
    </div>
    <div class="flex justify-between items-center">
        <h2 class="text-center text-3xl font-bold mb-6">Price Verification</h2>
        {{-- <h2 class="text-center text-xl font-bold mb-6">You have {{$errorData}} wrong inputted</h2> --}}
    </div>
    {{-- {{$errorData}}
    <pre>{{ var_dump($dataTemp)}}</pre> --}}
    <div class="w-full flex flex-end mb-4 space-x-2">
        @if($beforeVerified)
            <x-button class="w-2/12" wire:click="alertConfirm" wire:loading.attr="disabled">
                {{ __('Submit') }}
            </x-button>
        @else
            <x-button class="w-2/12" disabled>
                {{ __('Submit') }}
            </x-button>
        @endif
        @if($submitBtn!=0)
            <form action="{{ route('export.updatedfile', ['marketplace' => $marketplace, 'brand' => $brand]) }}" method="POST">
                @csrf
                <input type="hidden" value="{{ json_encode($dataTemp) }}" name="data">
                <button type="submit" wire:click="clearTemp" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm text-white uppercase bg-emerald-600 hover:bg-emerald-500 active:bg-emerald-500 focus:border-emerald-500 justify-center tracking-widest focus:outline-none focus:ring ring-emerald-300 disabled:opacity-25 transition ease-in-out duration-150">Download</button>
            </form>
            
        @endif
    </div>
    <x-table>
        <x-thead>
            <tr>
                <x-th>
                    SKU
                </x-th>
                <x-th>
                    Product Name
                </x-th>
                <x-th>
                    Price
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
                <tr class="hover:bg-gray-100 first-letter:bg-white border-b" wire:key="text-key-{{ $index }}-{{ time() }}">
                    <x-th scope="row">
                        {{ $item['sku'] }}
                    </x-th>
                    <x-td>
                        {{ $item['product_name'] }}
                    </x-td>
                    <x-td class="flex justify-end items-center">
                        @if($item['price'] < $item['average_discount'] && $item['is_whitelist']==false)
                            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        @endif
                        <input type="text" id="discount_price" value="{{ $dataTemp[$index]['price'] }}"
                            wire:keydown.enter="changePrice({{ $index }}, $event.target.value)"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </x-td>
                    <x-td>
                        {{ $item['average_discount'] }}
                    </x-td>
                    <x-td class="content-center">
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="dataTemp.{{ $index }}.is_whitelist" value=""
                                wire:change="changeWhitelist({{ $dataTemp[$index]['id'] }}, $event.target.checked)"
                                class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="default-checkbox"
                                class="ml-2 text-sm font-medium text-gray-900">Whitelist</label>
                        </div>
                    </x-td>
                </tr>
            @empty
                <tr>
                    <x-th scope="row" colspan="6" class="text-center text-gray-900">
                        All data is good!, you can continue to download it
                    </x-th>
                </tr>
            @endforelse
        </tbody>
    </x-table>
    {{-- <div class="p-4">
        {{ $catalogPriceTemp->links('livewire.pagination') }}
    </div> --}}
</div>
