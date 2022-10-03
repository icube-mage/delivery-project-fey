<div class="w-full flex flex-col">
    @if (session('status'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-md" role="alert">
            {{ session('status') }}
        </div>
    @endif
    @if (session('error'))
        <div class="p-4 mb-4 text-sm text-rose-700 bg-rose-100 rounded-md" role="alert">
            {{ session('error') }}
        </div>
    @endif
    @if ($errorMsg != null)
        <div wire:key="{{ rand() }}" x-data="{ show: true }" x-show="show"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform -translate-x-40"
            class="text-rose-700 px-6 py-4 border-0 rounded relative mb-4 bg-rose-100">
            <span class="text-xl inline-block mr-5 align-middle">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </span>
            <span class="inline-block align-middle mr-8">
                <b class="capitalize">Error</b>
                {{ $errorMsg }}
            </span>
            <button @click="show = false"
                class="absolute bg-transparent text-2xl font-semibold leading-none right-0 top-0 mt-4 mr-6 outline-none focus:outline-none">
                <span>×</span>
            </button>
        </div>
    @endif
    <div class="flex justify-between items-end">
        <form wire:submit.prevent="submit" class="my-6 w-2/5 flex flex-col items-end" enctype="multipart/form-data">
            <div class="form-group w-full space-y-3 mb-5">
                <div class="form-row w-full flex items-center justify-between">
                    <x-label for="brand" :value="__('Brand')" />
                    <x-select id="brand" wire:model.defer="brand">
                        <option value="" selected>-- Select Brand --</option>
                        @foreach ($brands as $item)
                            <option value="{{ $item->slug }}">{{ $item->name }}</option>
                        @endforeach
                    </x-select>
                </div>
                <div class="form-row w-full flex items-center justify-between">
                    <x-label for="marketplace" :value="__('Marketplace')" />
                    <x-select id="marketplace" wire:model.lazy="marketplace">
                        <option value="" selected>-- Select Marketplace --</option>
                        @foreach ($marketplaces as $item)
                            <option value="{{ $item->slug }}">{{ $item->name }}</option>
                        @endforeach
                    </x-select>
                </div>
                <div class="form-row w-full flex items-center justify-between">
                    <x-label for="file" :value="__('Upload File')" />
                    <div class="w-7/12 flex justify-start items-center">
                        <div class="w-1/12 py-2 pl-0.5 bg-gray-50 rounded-l-lg border-y border-l border-gray-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div class="w-11/12">
                            <label for="file" class="flex justify-start items-center text-sm text-gray-900 bg-gray-50 rounded-r-lg border-y border-r border-gray-300 cursor-pointer p-2 focus:outline-none">
                                @if ($file) 
                                    <span class="truncate">
                                    {{explode(".",$file->getClientOriginalName())[0]}}
                                    </span>
                                    <span class="font-bold">
                                    .{{explode(".",$file->getClientOriginalName())[1]}}
                                    </span>
                                @else
                                    Select file
                                @endif
                            </label>
                            <input id="file" wire:model.defer="file" class="hidden" type="file"  accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,text/comma-separated-values, text/csv, application/csv">
                        </div>
                    </div>
                    
                </div>
            </div>
            @if (!$submitBtn)
                <x-button class="w-7/12 cursor-not-allowed" disabled>
                    {{ __('Upload') }}
                </x-button>
            @else
                <x-button class="w-7/12" wire:loading.attr="disabled">
                    {{ __('Upload') }}
                </x-button>
            @endif
        </form>
        @if (!$isUploaded)
            <x-button class="inline-flex h-fit mb-6 cursor-not-allowed" disabled>Check Price</x-button>
        @else
            <a href="{{ route('menu.uploadfile.checkprice') }}"
                class="inline-flex h-fit mb-6 items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm text-white uppercase bg-sky-600 hover:bg-sky-500 active:bg-sky-500 focus:border-sky-500 justify-center tracking-widest focus:outline-none focus:ring ring-sky-300 disabled:opacity-25 transition ease-in-out duration-150">
                Check Price
            </a>
        @endif
    </div>

    <livewire:table.upload-file isUploaded="{{ $isUploaded }}" />
</div>
