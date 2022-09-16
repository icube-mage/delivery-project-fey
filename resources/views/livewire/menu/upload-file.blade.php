<div class="w-full flex flex-col">
    @if(session('status'))
    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-md dark:bg-green-200 dark:text-green-800" role="alert">
        {{ session('status') }}
      </div>
    @endif
    <div class="flex justify-between items-end">
        <form wire:submit.prevent="submit" class="my-6 w-2/5 flex flex-col items-end" enctype="multipart/form-data">
            <div class="form-group space-y-3 mb-5">
                <div class="form-row w-full flex items-center justify-between">
                    <x-label for="brand" :value="__('Brand')" />
                    <x-select id="brand" wire:model="brand">
                        <option selected>-- Select Brand --</option>
                        @foreach ($brands as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </x-select>
                </div>
                <div class="form-row w-full flex items-center justify-between">
                    <x-label for="marketplace" :value="__('Marketplace')" />
                    <x-select id="marketplace" wire:model="marketplace">
                        <option selected>-- Select Marketplace --</option>
                        @foreach ($marketplaces as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </x-select>
                </div>
                <div class="form-row w-full flex items-center justify-between">
                    <x-label for="file" :value="__('Upload File')" />
                    <input wire:model="file"
                        class="block w-7/12 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer p-2 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        id="file" type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,text/comma-separated-values, text/csv, application/csv">
                </div>
            </div>
            <x-button class="w-6/12">
                {{ __('Upload') }}
            </x-button>
        </form>
        <a href="{{ route('menu.uploadfile.checkprice') }}" class="inline-flex h-fit mb-6 items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm text-white uppercase bg-pink-600 hover:bg-pink-500 active:bg-pink-500 focus:border-pink-500 justify-center tracking-widest focus:outline-none focus:ring ring-pink-300 disabled:opacity-25 transition ease-in-out duration-150">Check Price</a>
    </div>
    <livewire:table.upload-file />
</div>
