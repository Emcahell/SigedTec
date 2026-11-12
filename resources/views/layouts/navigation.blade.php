<nav x-data="{ open: false }" class="bg-white border-b border-blue-100">
    @php($homePath = Auth::user()->role?->homePath() ?? '/')

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ $homePath }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-blue-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if (Auth::user()->isAdmin())
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            <x-icon-panel class="text-blue-600" />
                            {{ __('Panel') }}
                        </x-nav-link>

                        <x-nav-link :href="route('equipments.index')" :active="request()->routeIs('equipments.*')">
                            <x-icon-computer class="text-blue-600" />
                            {{ __('Equipos') }}
                        </x-nav-link>

                        <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                            <x-icon-tag class="text-blue-600" />
                            {{ __('Categorías') }}
                        </x-nav-link>

                        <x-nav-link :href="route('requests.index')" :active="request()->routeIs('requests.*')">
                            <x-icon-document class="text-blue-600" />
                            {{ __('Solicitudes') }}
                        </x-nav-link>

                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                            <x-icon-users class="text-blue-600" />
                            {{ __('Usuarios') }}
                        </x-nav-link>
                    @endif

                    @if (Auth::user()->isSolicitante())
                        <x-nav-link :href="route('requests.mine')" :active="request()->routeIs('requests.mine', 'requests.create', 'requests.show')">
                            <x-icon-document class="text-blue-600" />
                            {{ __('Mis solicitudes') }}
                        </x-nav-link>

                        <x-nav-link :href="route('equipments.mine')" :active="request()->routeIs('equipments.mine')">
                            <x-icon-computer class="text-blue-600" />
                            {{ __('Mis equipos') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-1 px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <x-icon-user class="text-blue-600" />
                            <div>{{ Auth::user()->name }}</div>
                            <x-icon-chevron-down />
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="inline-flex items-center gap-2">
                            <x-icon-user class="text-blue-600" />
                            {{ __('Mi perfil') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    class="inline-flex items-center gap-2 text-red-600 hover:bg-red-50 focus:bg-red-50"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <x-icon-logout class="text-red-600" />
                                {{ __('Cerrar sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-blue-500 hover:text-blue-700 hover:bg-blue-50 focus:outline-none focus:bg-blue-50 focus:text-blue-700 transition duration-150 ease-in-out" aria-label="{{ __('Abrir menú') }}">
                    <x-icon-bars x-show="! open" />
                    <x-icon-x-mark x-show="open" />
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if (Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Panel') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('equipments.index')" :active="request()->routeIs('equipments.*')">
                    {{ __('Equipos') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                    {{ __('Categorías') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('requests.index')" :active="request()->routeIs('requests.*')">
                    {{ __('Solicitudes') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                    {{ __('Usuarios') }}
                </x-responsive-nav-link>
            @endif

            @if (Auth::user()->isSolicitante())
                <x-responsive-nav-link :href="route('requests.mine')" :active="request()->routeIs('requests.mine', 'requests.create', 'requests.show')">
                    {{ __('Mis solicitudes') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('equipments.mine')" :active="request()->routeIs('equipments.mine')">
                    {{ __('Mis equipos') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Mi perfil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            class="!text-red-600 hover:!bg-red-50"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        <x-icon-logout class="inline text-red-600" />
                        {{ __('Cerrar sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
