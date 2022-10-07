<div class="w-1/2 2xl:w-1/3 py-4">
    @foreach($marketplaces as $key => $value)
    <div class="flex gap-4 items-center justify-between mb-3">
        <div class="flex items-center justify-around gap-4 w-full">
            <x-label>Name</x-label>
            <div class="grid w-full">
                <input type="text" class="w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring focus:ring-blue-200 transition duration-200" value="{{ $value->name }}" placeholder="Brand name">
                @error('name.') <span class="text-red-600 text-right">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="flex">
            <button class="p-3 bg-red-600 rounded-lg hover:bg-red-700 text-white" wire:click.prevent="destroy({{$value->id}})">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        </div>
    </div>
    @endforeach
    <form>
        <div class="flex gap-4 items-center justify-between">
            <div class="flex items-center justify-around gap-4 w-full">
                <x-label>Name</x-label>
                <div class="grid w-full">
                    <input type="text" class="w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring focus:ring-blue-200 transition duration-200" wire:model="marketplace" placeholder="Marketplace name">
                    @error('marketplace') <span class="text-red-600 text-right">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="flex items-center justify-between gap-4">
                {{-- <button class="p-3 bg-violet-600 rounded hover:bg-violet-700 text-white" wire:click.prevent="add({{$i}})">+</button> --}}
                <button class="p-3 bg-sky-600 rounded-lg hover:bg-sky-700 text-white" type="button" wire:click.prevent="store()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </button>
            </div>
        </div>
    </form>
</div>
