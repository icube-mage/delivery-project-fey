<x-content-card id="user-management-wrapper" x-data="{show: $wire.entangle('showModal')}">
    <div class="flex justify-between items-center mb-6">
    <x-input type="text" placeholder="Search" wire:model="searchTerm" />
    <x-button @click="show=true" wire:click="clearForm" type="button">Create User</x-button>
    </div>
    <x-table>
        <x-thead>
            <tr>
                <x-th>Name</x-th>
                <x-th>Username</x-th>
                <x-th>Email</x-th>
                <x-th>Created at</x-th>
                <x-th>Action</x-th>
            </tr>
        </x-thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <x-th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                    {{ $user->name }}
                </x-th>
                <x-td>
                    {{ $user->username }}
                </x-td>
                <x-td>
                    {{ $user->email }}
                </x-td>
                <x-td>
                    {{ \Carbon\Carbon::parse($user->created_at)->format('j F, Y') }}
                </x-td>
                <x-td>
                    <button type="button" @click="show=true" class="bg-orange-600 text-white rounded px-3 py-1 text-sm hover:bg-red-700" wire:click="edit({{$user}})">Edit</button>
                    @if(auth()->user()->id != $user->id)
                        <button type="button" class="bg-red-600 text-white rounded px-3 py-1 text-sm hover:bg-red-700" wire:click="alertDeleteConfirm({{$user->id}})">Delete</button>
                    @endif
                </x-td>
            </tr>
            @endforeach
        </tbody>
    </x-table>
    <div class="p-4">
    {{ $users->links('livewire.pagination') }}
    </div>

    {{-- Modal create form --}}
    {{-- @click.outside="show = false"  --}}
    <div x-show="show" x-transition x-cloak class="absolute inset-0 flex items-center justify-center bg-gray-700 bg-opacity-50 pl-24 transition-opacity duration-2000 linear">
        <div class="w-full h-screen p-6 bg-white  transition-all duration-200 ease-out">
            <div class="flex items-center justify-between">
                <h3 class="text-2xl">{{$titleAction}} User</h3>
                <svg @click="show=false"  wire:click="hideModal" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 cursor-pointer" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="my-4">
                <form wire:submit.prevent="alertConfirm">
                    <div class="flex justify-between items-center gap-4 w-1/2 mb-4">
                        <x-label>Fullname</x-label>
                        <div class="grid w-2/3">
                            <x-input type="text" wire:model.lazy="name" placeholder="Fullname" required/>
                            @error('name') <span class="text-red-600 text-right">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="flex justify-between items-center gap-4 w-1/2 mb-4">
                        <x-label>Username</x-label>
                        <div class="grid w-2/3">
                            @if($titleAction == 'Update')
                                <x-input type="text" wire:model.lazy="username" readonly class="bg-gray-300" placeholder="Username to login" required autocomplete="off" />
                            @else
                                <x-input type="text" wire:model.lazy="username" placeholder="Username to login" autocomplete="off" />
                            @endif
                            @error('username') <span class="text-red-600 text-right">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="flex justify-between items-center gap-4 w-1/2 mb-4">
                        <x-label>Email</x-label>
                        <div class="grid w-2/3">
                            @if($titleAction == 'Update')
                                <x-input type="email" wire:model.lazy="email" readonly class="bg-gray-300" placeholder="john.doe@sirclo.com" requiredautocomplete="off"/>
                            @else
                                <x-input type="email" wire:model.lazy="email" placeholder="john.doe@sirclo.com" autocomplete="off"/>
                            @endif
                            @error('email') <span class="text-red-600 text-right">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="flex justify-between items-center gap-4 w-1/2 mb-4">
                        <x-label>Password</x-label>
                        <div class="grid w-2/3">
                            <x-input type="password" wire:model.lazy="password" placeholder="••••••••" />
                            @error('password') <span class="text-red-600 text-right">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="flex justify-between items-center gap-4 w-1/2 mb-4">
                        <x-label>Role</x-label>
                        <div class="grid w-2/3">
                            <select wire:model="role" class="rounded-lg border-gray-300 focus:border-blue-600 focus:ring focus:ring-blue-200 transition duration-200">
                                @if($titleAction=='Create')
                                <option value=''>-- choose --</option>
                                @endif
                                @foreach($roles as $rl)
                                    <option value="{{ $rl->name }}" wire:key="{{$rl->id}}">{{ $rl->name }}</option>
                                @endforeach
                            </select>
                            @error('role') <span class="text-red-600 text-right">{{ $message }}</span> @enderror
                        </div>
                    </div>
            
                    @if($titleAction == 'Update')
                        <div class="mb-4 text-orange-500">
                            Email and username are uniques, can't be changed
                        </div>
                    @endif

                    <div class="flex justify-between items-center gap-4 w-1/2">
                        <x-button-secondary type="button" @click="show=false" wire:click="hideModal">Cancel</x-button-secondary>
                        @if($canSubmit)
                            <x-button  wire:loading.attr="disabled">Save</x-button>
                        @else
                            <x-button type="button" class="cursor-not-allowed" disabled>Save</x-button>
                        @endif
                    </div>
            
                </form>
            </div>
        </div>
    </div>
</x-content-card>
