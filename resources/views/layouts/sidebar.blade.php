<aside class="w-1/5" aria-label="Sidebar">
    <div class="relative h-screen overflow-y-auto py-4 px-3 bg-gradient-to-b from-red-400 via-purple-500 to-blue-300 dark:bg-gray-800">
        <h3 class="text-center uppercase font-bold text-4xl text-white">Fey</h3>
        <ul class="mt-5 space-y-2">
            <li>
                <a href="/"
                    class="flex items-center p-2 text-white text-base font-medium rounded-lg hover:text-slate-700  dark:text-white hover:bg-pink-500 dark:hover:bg-pink-700 @if(URL::current() == url('/')) bg-pink-500 @endif">
                    <svg class="w-6 h-6 text-white transition duration-75 dark:text-gray-700 group-hover:text-gray-900 dark:group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>
            <li>
                <h3 class="text-gray-400 font-bold text-sm mb-2 uppercase mt-8 px-3">Menu</h3>
                <a href="{{ route('menu.uploadfile') }}"
                    class="flex items-center p-2 text-white text-base font-medium rounded-lg hover:text-slate-700 dark:text-white hover:bg-pink-500 dark:hover:bg-pink-700 @if(Route::is('menu.uploadfile') || Route::is('menu.uploadfile.checkprice')) bg-pink-500 @endif">
                    <svg class="w-6 h-6 text-white transition duration-75 dark:text-gray-700 group-hover:text-gray-900 dark:group-hover:text-white"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    <span class="ml-3">Upload File</span>
                </a>
            </li>
            <li>
                <a href="{{ route('menu.report') }}"
                    class="flex items-center p-2 text-base text-white font-medium rounded-lg hover:text-slate-700 dark:text-white hover:bg-pink-500 dark:hover:bg-pink-700 @if(Route::is('menu.report')) bg-pink-500 @endif">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="flex-1 ml-3 whitespace-nowrap">Report</span>
                </a>
            </li>
            <li>
                <a href="{{ route('menu.historicaldata') }}"
                    class="flex items-center p-2 text-base text-white font-medium rounded-lg hover:text-slate-700 dark:text-white hover:bg-pink-500 dark:hover:bg-pink-700 @if(Route::is('menu.historicaldata')) bg-pink-500 @endif">
                    <svg class="w-6 h-6 text-white transition duration-75 dark:text-gray-700 group-hover:text-gray-900 dark:group-hover:text-white"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="flex-1 ml-3 whitespace-nowrap">Historical Data</span>
                </a>
            </li>
            <li>
                <h3 class="text-gray-400 font-bold text-sm mb-2 uppercase mt-8 px-3">Settings</h3>
                <a href="{{ route('settings.configuration') }}"
                    class="flex items-center p-2 text-base text-white font-medium rounded-lg hover:text-slate-700  dark:text-white hover:bg-pink-500 dark:hover:bg-pink-700 @if(Route::is('settings.configuration')) bg-pink-500 @endif">
                    <svg class="w-6 h-6 text-white transition duration-75 dark:text-gray-700 group-hover:text-gray-900 dark:group-hover:text-white"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="flex-1 ml-3 whitespace-nowrap">Configuration</span>
                </a>
            </li>
        </ul>
        <div class="inset-x-3 absolute bottom-0 ">
            <a href="{{ route('user.manage') }}" class="text-white font-medium flex items-center justify-center bg-gradient-to-r from-pink-500 to-yellow-500 hover:from-green-400 hover:to-blue-500 rounded-lg px-2 py-3 mb-3  text-center">
                User Management
            </a>
        </div>
    </div>
</aside>
