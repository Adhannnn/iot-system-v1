<div class="h-screen">

    <!-- Sidebar !-->
    <div class="flex flex-col items-center h-full overflow-hidden text-gray-800">
        <!-- App Logo/Title -->
        <div class="flex items-center w-full px-3 mt-3">
            <a href="#" class="flex items-center">
                <svg class="w-8 h-8 fill-current text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path
                        d="M11 17a1 1 0 001.447.894l4-2A1 1 0 0017 15V9.236a1 1 0 00-1.447-.894l-4 2a1 1 0 00-.553.894V17zM15.211 6.276a1 1 0 000-1.788l-4.764-2.382a1 1 0 00-.894 0L4.789 4.488a1 1 0 000 1.788l4.764 2.382a1 1 0 00.894 0l4.764-2.382zM4.447 8.342A1 1 0 003 9.236V15a1 1 0 00.553.894l4 2A1 1 0 009 17v-5.764a1 1 0 00-.553-.894l-4-2z" />
                </svg>
                <span class="ml-2 text-sm font-bold">Monitoring System V1.0</span>
            </a>
        </div>

        <!-- Rooms -->
        <div class="w-full px-2 mt-6">
            <div class="w-full border-t border-gray-200 pt-4">
                <div class="text-sm font-medium text-gray-500 px-3 mb-2">Rooms</div>

                @foreach ($rooms as $room)
                    <button wire:click="selectRoom({{ $room->id }})"
                        class="w-full text-left px-4 py-2 rounded hover:bg-gray-100 transition 
                        {{ $room->id == $currentRoomId ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-800' }}">
                        {{ $room->name }}
                    </button>
                @endforeach

                <!-- Add Room -->
                <button x-on:click="$dispatch('open-add-room')"
                    class="flex items-center w-full px-4 py-2 mt-2 text-sm rounded hover:bg-gray-100 text-blue-600 font-semibold">
                    + Add Room
                </button>
            </div>

            <!-- Settings -->
            <div class="w-full border-t border-gray-200 pt-4 mt-4">
                {{-- <a href="{{ route('settings') }}"
                    class="flex items-center w-full px-4 py-2 text-sm rounded hover:bg-gray-100 transition
              {{ request()->routeIs('settings') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-800' }}">
                    <svg class="w-5 h-5 mr-2 stroke-current {{ request()->routeIs('settings') ? 'text-blue-700' : 'text-gray-600' }}"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    Settings
                </a> --}}
            </div>

        </div>

        <!-- Log Out -->
        <a class="flex items-center justify-center w-full h-16 mt-auto hover:bg-gray-100 cursor-pointer" x-data
            @click.prevent="
                confirmLogout
            ">
            <svg class="w-6 h-6 stroke-current text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span class="ml-2 text-sm font-medium">Log Out</span>
        </a>

        <!-- Hidden logout form -->
        <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
            @csrf
        </form>

        <!-- Modal -->
        <div x-data="{ open: false }" @open-add-room.window="open = true" x-show="open" x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
            <div @click.away="open = false" class="bg-white rounded-lg p-6 w-96">
                <h2 class="text-xl font-bold mb-4">Add New Room</h2>

                <form method="POST" action="{{ route('rooms.store') }}" x-data="{ loading: false }"
                    @submit="loading = true">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Room Name</label>
                        <input type="text" name="name" class="w-full border rounded p-2" required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="button" @click="open = false"
                            class="px-4 py-2 bg-gray-300 rounded mr-2">Cancel</button>

                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded flex items-center"
                            :disabled="loading">
                            <svg x-show="loading" class="animate-spin h-5 w-5 mr-2 text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span x-text="loading ? 'Processing...' : 'Done'"></span>
                        </button>
                    </div>
                </form>

                @if (session('success'))
                    <script>
                        Swal.fire({
                            icon: "success",
                            title: "Success!",
                            text: "Room added successfully",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    </script>
                @endif

                @if ($errors->any())
                    <script>
                        Swal.fire({
                            icon: "error",
                            title: "Oops!",
                            text: "Room's name already exists!",
                            showConfirmButton: true,
                            confirmButtonText: "Close"
                        });
                    </script>
                @endif
            </div>
        </div>

        <script>
            function confirmLogout() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You will go out from here",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, logout!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('logout-form').submit();
                    }
                });
            }
        </script>
    </div>

</div>
