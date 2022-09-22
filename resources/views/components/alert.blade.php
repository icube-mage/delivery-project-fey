<div x-data="{ show: true }" x-show="show"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-x-0"
    x-transition:leave-end="opacity-0 transform -translate-x-40"
    class="text-white px-6 py-4 border-0 rounded relative mb-4 bg-{{ $color ?? 'yellow' }}-500">
    <span class="text-xl inline-block mr-5 align-middle">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    </span>
    <span class="inline-block align-middle mr-8">
        <b class="capitalize">{{ $status }}</b> 
        {{ $slot }}
    </span>
    <button @click="show = false" class="absolute bg-transparent text-2xl font-semibold leading-none right-0 top-0 mt-4 mr-6 outline-none focus:outline-none">
        <span>×</span>
    </button>
</div>