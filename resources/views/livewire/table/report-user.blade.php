<div>
    <div class="flex justify-between items-center mb-6">
        <a class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm text-white uppercase bg-emerald-600 hover:bg-emerald-500 active:bg-emerald-500 focus:border-emerald-500 justify-center tracking-widest focus:outline-none focus:ring ring-emerald-300 disabled:opacity-25 transition ease-in-out duration-150" href="">Download</a>
        <x-input type="text" placeholder="Search" wire:model="searchTerm" />
    </div>
    <div class="flex items-center bg-gray-400 border p-4">
        <div class="text-center w-1/2 font-bold">User</div>
        <div class="text-center w-1/2 font-bold">Wrong Inputted Price</div>
    </div>
    <div x-data="{ openedIndex: 0 }" class="flex flex-col">
        @foreach($users as $index => $user)
        <div @click="openedIndex == 1 ? openedIndex = -1 : openedIndex = 1" class="flex items-center justify-between bg-white border p-4">
            <div class="text-center w-1/2">{{$user->name}}</div>
            <div class="text-center w-1/2">{{$user->uploadHistory->sum('false_price')}}</div>
            <div>
                <template x-if="openedIndex == 1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </template>
                <template x-if="openedIndex != 1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                </template>
            </div>
        </div>
        <div x-show.transition.in.duration.800ms="openedIndex == 1" class="border">
            <x-table>
                <x-thead>
                    <tr>
                        <x-th>Brand</x-th>
                        <x-th>Marketplace</x-th>
                        <x-th>SKU</x-th>
                        <x-th>Price</x-th>
                        <x-th>Average</x-th>
                    </tr>
                </x-thead>
                <tbody>
                    @foreach($user->uploadHistory as $uploadHistory)
                        @php
                            $histories = json_decode($uploadHistory->extras)
                        @endphp
                        @foreach($histories as $history)
                        <tr class="hover:bg-gray-100">
                            <x-td>
                                {{ $uploadHistory->brand }}
                            </x-td>
                            <x-td>
                                {{ $uploadHistory->marketplace }}
                            </x-td>
                            <x-td>
                                {{ $history->sku }}
                            </x-td>
                            <x-td>
                                {{ $history->price }}
                            </x-td>
                            <x-td>
                                {{ $history->average_discount }}
                            </x-td>
                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </x-table>
        </div>
        @endforeach
    </div>
</div>
