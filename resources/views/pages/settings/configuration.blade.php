<x-app-layout title="Configuration">
    <x-content-card>
        <div 
            x-data="{
            openTab: @if(session()->has('tab')) {{ session()->get('tab') }} @else 1 @endif,
            activeClasses: 'border-l border-t border-r rounded-t text-blue-700',
            inactiveClasses: 'text-blue-500 hover:text-blue-800'
            }" 
            class="p-1"
        >
            <ul class="flex border-b">
                <li @click="openTab = 1" :class="{ '-mb-px': openTab === 1 }" class="-mb-px mr-1">
                    <a :class="openTab === 1 ? activeClasses : inactiveClasses" class="bg-white inline-block py-2 px-4 font-semibold" href="#">
                        <span class="sm:flex text-yellow-600">General</span>
                    </a>
                </li>
                <li @click="openTab = 2" :class="{ '-mb-px': openTab === 2 }" class="mr-1">
                    <a :class="openTab === 2 ? activeClasses : inactiveClasses" class="bg-white inline-block py-2 px-4 font-semibold" href="#">
                        <span class="sm:flex text-yellow-600">Tokopedia</span>
                    </a>
                </li>
                <li @click="openTab = 3" :class="{ '-mb-px': openTab === 3 }" class="mr-1">
                    <a :class="openTab === 3 ? activeClasses : inactiveClasses" class="bg-white inline-block py-2 px-4 font-semibold" href="#">
                        <span class="sm:flex text-yellow-600">Shopee</span>
                    </a>
                </li>
            </ul>
            <div class="w-full pt-4">
            
                <div x-cloak x-show="openTab === 1">
                    <livewire:configuration.general/>
                </div>
                <div x-cloak x-show="openTab === 2">
                    <livewire:configuration.tokopedia/>
                </div>
                <div x-cloak x-show="openTab === 3">
                    Shopee
                </div>
            </div>
        </div>
        
    </x-content-card>
</x-app-layout>