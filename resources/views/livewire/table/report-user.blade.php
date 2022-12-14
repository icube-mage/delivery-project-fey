<div>
    <div class="flex justify-between items-center mb-6">
        <a class="excel-btn" href="{{route('export.report')}}">Download</a>
        <x-input type="text" placeholder="Search" wire:model="searchTerm" />
    </div>
    <div class="flex items-center bg-gray-400 border border-gray-400 p-4">
        <div class="text-center w-1/2 font-bold">User</div>
        <div class="text-center w-1/2 font-bold">Wrong Inputted Price</div>
    </div>
    @foreach($users as $index => $user)
    <div x-data="{ openedIndex: {{'-'.$index+1}} }" class="flex flex-col">
        <div @click="openedIndex == {{$index+1}} ? openedIndex = {{'-'.$index+1}} : openedIndex = {{$index+1}}" class="flex items-center justify-between bg-white border p-4">
            <div class="text-center w-1/2">{{$user->name}}</div>
            <div class="text-center w-1/2">{{$user->uploadHistory->sum('false_price')}}</div>
            <div>
                <template x-if="openedIndex == {{$index+1}}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                </template>
                <template x-if="openedIndex != {{$index+1}}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </template>
            </div>
        </div>
        <div x-cloak x-show.transition.in.duration.800ms="openedIndex == {{$index+1}}" class="border p-2 bg-slate-200">
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
                    @forelse($user->uploadHistory as $uploadHistory)
                        @php
                            $histories = json_decode($uploadHistory->extras)
                        @endphp
                        @foreach($histories as $history)
                        <tr class="hover:bg-gray-100 bg-white">
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
                    @empty
                        <tr class="hover:bg-gray-100 bg-white">
                            <x-td colspan="5" class="text-center">
                                -- No logs for this user were found--
                            </x-td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table>
        </div>
    </div>
    @endforeach
</div>
