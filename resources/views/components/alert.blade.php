<div x-data="{ show: true }" x-show="show"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-x-0"
    x-transition:leave-end="opacity-0 transform -translate-x-40"
    class="text-white px-6 py-4 border-0 rounded relative mb-4 bg-{{ $color ?? 'yellow' }}-500">
    <span class="text-xl inline-block mr-5 align-middle">
        
    </span>
    <span class="inline-block align-middle mr-8">
        <b class="capitalize">{{ $status }}</b> 
        {{ $slot }}
    </span>
    <button @click="show = false" class="absolute bg-transparent text-2xl font-semibold leading-none right-0 top-0 mt-4 mr-6 outline-none focus:outline-none">
        <span>×</span>
    </button>
</div>