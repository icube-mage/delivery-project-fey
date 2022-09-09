<header class="w-full items-center bg-white py-2 px-6 flex">
    <div class="w-1/2 flex items-center justify-start">
        <a href="{{ route('dashboard') }}">
            <div class="py-2 px-3 uppercase text-violet-700 font-bold text-lg hover:text-violet-600">
                {{ $title }}
            </div>
        </a>
        <a href="{{ route('calculate.index') }}" class="text-base">XXX</a>
    </div>
    <div x-data="{ isOpen: false }" class="relative w-1/2 flex justify-end">
        <button @click="isOpen = !isOpen" class="realtive z-10 w-12 h-12 rounded-full overflow-hidden border-4 border-gray-400 hover:border-gray-300 focus:border-gray-300 focus:outline-none">
            <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
        </button>
        <button x-show="isOpen" @click="isOpen = false" class="h-full w-full fixed inset-0 cursor-default"></button>
        <div x-cloak x-show="isOpen" class="absolute w-48 bg-white rounded-lg shadow-lg py-2 mt-16">
            {{-- <a href="{{ route("user.edit", Auth::user()->id) }}" class="block px-4 py-2 account-link hover:text-white">My Account</a> --}}
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="block w-full text-left px-4 py-2 account-link hover:text-gray-400 transition ease-in-out duration-200">Sign Out</button>
            </form>
        </div>
    </div>
</header>