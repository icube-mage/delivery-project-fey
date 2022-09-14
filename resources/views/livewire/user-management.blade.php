<x-content-card  x-data="{show:false}">
    <div class="flex justify-between items-center mb-6">
    <x-input type="text" placeholder="Search" wire:model="searchTerm" />
    <x-button @click="show=true" wire:click="clear" type="button">Create User</x-button>
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
                <x-th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
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
    {{ $users->links('livewire.pagination') }}

    {{-- Modal create form --}}
    <div x-show="show" @click.outside="show = false" x-cloak class="absolute inset-0 flex items-center justify-center bg-gray-700 bg-opacity-50 pl-24 transition-opacity duration-2000 linear">
        <div class="w-full h-screen p-6 bg-white  transition-all duration-200 ease-out">
            <div class="flex items-center justify-between">
                <h3 class="text-2xl">{{$titleAction}} User</h3>
                <svg @click="show=false" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 cursor-pointer" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="my-4">
                <form>
                    <div class="flex justify-between items-center gap-4 w-1/2 mb-4">
                        <x-label>Fullname</x-label>
                        <div class="grid">
                            <x-input type="text" wire:model="name" placeholder="Fullname" />
                            @error('name') <span class="text-red-600 text-right">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="flex justify-between items-center gap-4 w-1/2 mb-4">
                        <x-label>Username</x-label>
                        <div class="grid">
                            <x-input type="text" wire:model="username" placeholder="Username to login" />
                            @error('username') <span class="text-red-600 text-right">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="flex justify-between items-center gap-4 w-1/2 mb-4">
                        <x-label>Email</x-label>
                        <div class="grid">
                            <x-input type="email" wire:model="email" placeholder="john.doe@sirclo.com" />
                            @error('email') <span class="text-red-600 text-right">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="flex justify-between items-center gap-4 w-1/2 mb-4">
                        <x-label>Password</x-label>
                        <div class="grid">
                            <x-input type="password" wire:model="password" placeholder="••••••••" />
                            @error('password') <span class="text-red-600 text-right">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="flex justify-between items-center gap-4 w-1/2 mb-4">
                        <x-label>Role</x-label>
                        <div class="grid">
                            <select wire:model="role" class="rounded-lg border-gray-300 focus:border-blue-600 focus:ring focus:ring-blue-200 transition duration-200">
                                <option value=''>-- choose --</option>
                                @foreach($roles as $rl)
                                    <option value="{{ $rl->name }}">{{ $rl->name }}</option>
                                @endforeach
                            </select>
                            @error('role') <span class="text-red-600 text-right">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex justify-between items-center gap-4 w-1/2">
                <x-button-secondary @click="show=false">Cancel</x-button-secondary>
                <x-button type="button" wire:click="alertConfirm">Save</x-button>
            </div>
        </div>
    </div>
</x-content-card>
