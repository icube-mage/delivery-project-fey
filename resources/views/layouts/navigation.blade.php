<header class="w-full items-center bg-white py-2 px-6 flex border-b">
    <div class="w-1/2 flex items-center justify-start">
        <h3 class="text-violet-700 font-bold text-lg hover:text-violet-600">
        >> 
        @if(!$title && auth()->user()->roles[0]->name) 
            {{auth()->user()->roles[0]->name}} 
        @else 
            {{$title}} 
        @endif
        </h3>
    </div>
    <div x-data="{ isOpen: false }" class="relative w-1/2 flex justify-end">
        <button @click="isOpen = !isOpen" id="dropdownDividerButton" class="font-medium text-sm px-4 py-2.5 text-center inline-flex items-center hover:text-gray-500" type="button">{{auth()->user()->name}} 
        <svg class="ml-2 w-4 h-4" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></button>
        <div x-cloak x-show="isOpen" class="absolute w-48 bg-white rounded-lg shadow-lg border py-2 mt-8">
            <a href="#" class="block px-4 py-1 account-link hover:text-gray-500">My Account</a>
            <div class="my-1 border-t border-gray-200 dark:border-gray-700"></div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="block w-full text-left px-4 py-1 account-link hover:text-gray-500 transition ease-in-out duration-200">Sign Out</button>
            </form>
        </div>
    </div>
</header>