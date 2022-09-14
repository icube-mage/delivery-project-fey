<div class="w-full flex flex-col">
    <h3 class="text-3xl font-bold">{{ $title }}</h3>
    <form wire:submit.prevent="submit" class="my-6 w-2/5 flex flex-col items-end" enctype="multipart/form-data">
        <div class="form-group space-y-3 mb-5">
            <div class="form-row w-full flex items-center justify-between">
                <x-label for="brand" :value="__('Brand')" />
                <select id="brand" wire:model="brand"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-7/12 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected>-- Select Brand --</option>
                    @foreach ($brands as $key => $value)
                        <option value="{{ $key }}">{{ $value->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-row w-full flex items-center justify-between">
                <x-label for="marketplace" :value="__('Marketplace')" />
                <select id="marketplace" wire:model="marketplace"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-7/12 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected>-- Select Marketplace --</option>
                    @foreach ($marketplaces as $key => $value)
                        <option value="{{ $key }}">{{ $value->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-row w-full flex items-center justify-between">
                <x-label for="file" :value="__('Upload File')" />
                <input wire:model="file"
                    class="block w-7/12 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer p-2 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                    id="file" type="file">
            </div>
        </div>
        <x-button class="w-6/12">
            {{ __('Upload') }}
        </x-button>
    </form>
    <livewire:table.upload-file />
</div>
