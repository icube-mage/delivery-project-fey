<div x-data="{ show: false }" class="flex gap-1">
    <div class="w-1/2 2xl:w-2/5">
        @foreach($maps as $index => $map)
        <div class="flex items-start justify-between gap-6 py-4 border-b">
            <x-label>{{$map['marketplace']}} Excel</x-label>
            <div class="flex items-start justify-between gap-4">
                <textarea class="rounded-lg border-gray-300 focus:border-blue-600 focus:ring focus:ring-blue-200 transition duration-200" rows="3" cols="30" wire:model="config.{{$index}}"></textarea>
                <x-button type="button" wire:click="store('{{$map['config']}}', {{$index}})" @click="show=true">Save</x-button>
            </div>
        </div>
        @endforeach
    </div>
    <div class="w-1/2 2xl:w-3/5 p-4">  
        <div class="bg-gray-100 border rounded-xl p-2">
            <p class="text-yellow-700 text-sm">Use equal symbol (=) to defined header name and separate by comma.<br/>mapping key : <br/>
            sku, product_name, retail_price, discount_price, warehouse and start_date*<br/>
            <br/>start_date & retail_price are optional</p>
        </div>
    </div>
    <div class="flex top-16 absolute right-4 items-center p-4 mb-4 w-full max-w-xs text-gray-500 bg-white rounded-lg shadow" role="alert"
        x-show="show"
        x-init="setTimeout(() => show = false, 2000)"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-x-0"
        x-transition:leave-end="opacity-0 transform -translate-x-40"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100">
        <div class="inline-flex flex-shrink-0 justify-center items-center w-8 h-8 text-green-500 bg-green-100 rounded-lg">
            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
            <span class="sr-only">Check icon</span>
        </div>
        <div class="ml-3 text-sm font-normal">Config changed successfully.</div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8" @click="show = false" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>
    </div>
</div>