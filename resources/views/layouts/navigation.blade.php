<nav x-data="{ open: false }" class="bg-[#3D1F00] border-b border-[#5C3317]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-[#F5F0EA] font-bold text-xl hover:text-[#8B6343] transition-colors">GlowBook</a>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center">
                    <a href="{{ route('salons.index') }}" class="text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium {{ request()->routeIs('salons.*') ? 'border-b-2 border-[#8B6343] text-[#8B6343]' : '' }}">
                        Salons
                    </a>
                    @auth
                        @if(Auth::user()->isClient())
                            <a href="{{ route('client.dashboard') }}" class="text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium {{ request()->routeIs('client.dashboard') ? 'border-b-2 border-[#8B6343] text-[#8B6343]' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('client.appointments.index') }}" class="text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium {{ request()->routeIs('client.appointments.*') ? 'border-b-2 border-[#8B6343] text-[#8B6343]' : '' }}">
                                My Appointments
                            </a>
                        @elseif(Auth::user()->isSpecialist())
                            <a href="{{ route('specialist.dashboard') }}" class="text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium {{ request()->routeIs('specialist.dashboard') ? 'border-b-2 border-[#8B6343] text-[#8B6343]' : '' }}">
                                My Schedule
                            </a>
                            <a href="{{ route('specialist.profile.show') }}" class="text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium {{ request()->routeIs('specialist.profile.*') ? 'border-b-2 border-[#8B6343] text-[#8B6343]' : '' }}">
                                My Profile
                            </a>
                            <a href="{{ route('specialist.reviews.index') }}" class="text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium {{ request()->routeIs('specialist.reviews.*') ? 'border-b-2 border-[#8B6343] text-[#8B6343]' : '' }}">
                                My Reviews
                            </a>
                        @elseif(Auth::user()->isSalonOwner())
                            <a href="{{ route('salon-owner.dashboard') }}" class="text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium {{ request()->routeIs('salon-owner.dashboard') ? 'border-b-2 border-[#8B6343] text-[#8B6343]' : '' }}">
                                My Salon
                            </a>
                            <a href="{{ route('salon-owner.services.index') }}" class="text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium {{ request()->routeIs('salon-owner.services.*') ? 'border-b-2 border-[#8B6343] text-[#8B6343]' : '' }}">
                                Services
                            </a>
                            <a href="{{ route('salon-owner.specialists.index') }}" class="text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium {{ request()->routeIs('salon-owner.specialists.*') ? 'border-b-2 border-[#8B6343] text-[#8B6343]' : '' }}">
                                Specialists
                            </a>
                            <a href="{{ route('salon-owner.appointments.index') }}" class="text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium {{ request()->routeIs('salon-owner.appointments.*') ? 'border-b-2 border-[#8B6343] text-[#8B6343]' : '' }}">
                                Appointments
                            </a>
                        @elseif(Auth::user()->isSuperAdmin())
                            <a href="{{ route('super-admin.dashboard') }}" class="text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium {{ request()->routeIs('super-admin.dashboard') ? 'border-b-2 border-[#8B6343] text-[#8B6343]' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('super-admin.salons.index') }}" class="text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium {{ request()->routeIs('super-admin.salons.*') ? 'border-b-2 border-[#8B6343] text-[#8B6343]' : '' }}">
                                Salons
                            </a>
                            <a href="{{ route('super-admin.users.index') }}" class="text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium {{ request()->routeIs('super-admin.users.*') ? 'border-b-2 border-[#8B6343] text-[#8B6343]' : '' }}">
                                Users
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            @auth
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <div x-data="{ openDropdown: false }" class="relative">
                        <button @click="openDropdown = !openDropdown" class="flex items-center gap-2 focus:outline-none">
                            <div class="bg-[#8B6343] text-[#F5F0EA] rounded-full w-8 h-8 flex items-center justify-center text-sm font-medium">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="text-[#F5F0EA] text-sm font-medium">{{ Auth::user()->name }}</span>
                            <svg class="fill-[#F5F0EA] h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="openDropdown" @click.away="openDropdown = false" x-cloak
                             class="absolute right-0 mt-2 w-48 bg-[#F5F0EA] border border-[#8B6343] rounded-lg shadow-lg py-1 z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-[#3D1F00] hover:bg-[#8B6343] hover:text-[#F5F0EA] transition-colors">Account Settings</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-[#3D1F00] hover:bg-[#8B6343] hover:text-[#F5F0EA] transition-colors">Log Out</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-[#F5F0EA] hover:text-[#8B6343] focus:outline-none transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @else
                <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                    <a href="{{ route('login') }}" class="text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium">Log in</a>
                    <a href="{{ route('register') }}" class="text-sm px-4 py-2 bg-[#8B6343] text-[#F5F0EA] rounded-md hover:bg-[#F5F0EA] hover:text-[#3D1F00] transition-colors font-medium">Register</a>
                </div>
            @endauth
        </div>
    </div>

    @auth
        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-[#3D1F00] border-t border-[#5C3317]">
            <div class="pt-2 pb-3 space-y-1 px-4">
                <a href="{{ route('salons.index') }}" class="block py-2 text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium">Salons</a>
                @if(Auth::user()->isClient())
                    <a href="{{ route('client.dashboard') }}" class="block py-2 text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium">Dashboard</a>
                    <a href="{{ route('client.appointments.index') }}" class="block py-2 text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium">My Appointments</a>
                @elseif(Auth::user()->isSpecialist())
                    <a href="{{ route('specialist.dashboard') }}" class="block py-2 text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium">My Schedule</a>
                    <a href="{{ route('specialist.profile.show') }}" class="block py-2 text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium">My Profile</a>
                    <a href="{{ route('specialist.reviews.index') }}" class="block py-2 text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium">My Reviews</a>
                @elseif(Auth::user()->isSalonOwner())
                    <a href="{{ route('salon-owner.dashboard') }}" class="block py-2 text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium">My Salon</a>
                    <a href="{{ route('salon-owner.services.index') }}" class="block py-2 text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium">Services</a>
                    <a href="{{ route('salon-owner.specialists.index') }}" class="block py-2 text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium">Specialists</a>
                    <a href="{{ route('salon-owner.appointments.index') }}" class="block py-2 text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium">Appointments</a>
                @elseif(Auth::user()->isSuperAdmin())
                    <a href="{{ route('super-admin.dashboard') }}" class="block py-2 text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium">Dashboard</a>
                    <a href="{{ route('super-admin.salons.index') }}" class="block py-2 text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium">Salons</a>
                    <a href="{{ route('super-admin.users.index') }}" class="block py-2 text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium">Users</a>
                @endif
            </div>
            <div class="pt-4 pb-3 border-t border-[#5C3317] px-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="bg-[#8B6343] text-[#F5F0EA] rounded-full w-8 h-8 flex items-center justify-center text-sm font-medium">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-medium text-sm text-[#F5F0EA]">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-xs text-[#D1C5B8]">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" class="block py-2 text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium">Account Settings</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left py-2 text-[#F5F0EA] hover:text-[#8B6343] transition-colors text-sm font-medium">Log Out</button>
                </form>
            </div>
        </div>
    @endauth
</nav>
