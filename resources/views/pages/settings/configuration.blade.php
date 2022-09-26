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
                        <span class="sm:flex text-yellow-600">Brand</span>
                    </a>
                </li>
                <li @click="openTab = 2" :class="{ '-mb-px': openTab === 2 }" class="mr-1">
                    <a :class="openTab === 2 ? activeClasses : inactiveClasses" class="bg-white inline-block py-2 px-4 font-semibold" href="#">
                        <span class="sm:flex text-yellow-600">Marketplace</span>
                    </a>
                </li>
                <li @click="openTab = 3" :class="{ '-mb-px': openTab === 3 }" class="mr-1">
                    <a :class="openTab === 3 ? activeClasses : inactiveClasses" class="bg-white inline-block py-2 px-4 font-semibold" href="#">
                        <span class="sm:flex text-yellow-600">Excel Column Map</span>
                    </a>
                </li>
                <li @click="openTab = 4" :class="{ '-mb-px': openTab === 4 }" class="mr-1">
                    <a :class="openTab === 4 ? activeClasses : inactiveClasses" class="bg-white inline-block py-2 px-4 font-semibold" href="#">
                        <span class="sm:flex text-yellow-600">Excel Row Map</span>
                    </a>
                </li>
                @if(auth()->user()->hasRole('Super Admin'))
                <li @click="openTab = 5" :class="{ '-mb-px': openTab === 5 }" class="mr-1">
                    <a :class="openTab === 5 ? activeClasses : inactiveClasses" class="bg-white inline-block py-2 px-4 font-semibold" href="#">
                        <span class="sm:flex text-yellow-600">General</span>
                    </a>
                </li>
                @endif
            </ul>
            <div class="w-full pt-4">
            
                <div x-cloak x-show="openTab === 1">
                    <livewire:configuration.brand-form/>
                </div>
                <div x-cloak x-show="openTab === 2">
                    <livewire:configuration.marketplace-form/>
                </div>
                <div x-cloak x-show="openTab === 3">
                    <livewire:configuration.mapping-excel/>
                </div>
                <div x-cloak x-show="openTab === 4">
                    <livewire:configuration.row-excel/>
                </div>
                <div x-cloak x-show="openTab === 5">
                    <livewire:configuration.general/>
                </div>
            </div>
        </div>
        
    </x-content-card>
</x-app-layout>