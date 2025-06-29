<aside x-data="{
    open: true,
    mobileOpen: false,
    closeSubmenus() {
        document.querySelectorAll('[x-data]').forEach(el => {
            if (el.__x && el.__x.$data.hasOwnProperty('submenuOpen')) {
                el.__x.$data.submenuOpen = false;
            }
        });
    }
}" class="bg-white dark:bg-gray-900 shadow h-screen fixed sm:static z-50 flex flex-col">

    {{-- Mobile Toggle --}}
    <div class="flex items-center justify-between p-4 sm:hidden">
        <h3 class="text-lg font-bold text-gray-700 dark:text-gray-200">Menu</h3>
        <button @click="mobileOpen = !mobileOpen" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    {{-- Sidebar Chính --}}
    <div :class="{'hidden': !mobileOpen, 'block': mobileOpen, 'sm:block': true}" class="flex flex-col h-full sm:h-auto">

        {{-- Collapse Button --}}
        <div class="hidden sm:flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 x-show="open" class="text-lg font-bold text-gray-700 dark:text-gray-200">Menu</h3>
            <button @click="open = !open; if(!open) closeSubmenus()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                <i class="fas" :class="open ? 'fa-chevron-left' : 'fa-chevron-right'"></i>
            </button>
        </div>

        {{-- Menu Loop --}}
        <ul class="space-y-2 p-4 flex-1" :class="open ? 'w-64' : 'w-20'">

            @foreach ($menu as $item)

                {{-- Nếu có children: submenu --}}
                @if (isset($item['children']))
                    @php
                        $activeSub = false;
                        foreach ($item['children'] as $child) {
                            if (isset($child['route']) && Route::has($child['route']) && request()->routeIs($child['route'])) {
                                $activeSub = true;
                            }
                        }
                    @endphp

                    <li x-data="{ submenuOpen: {{ $activeSub ? 'true' : 'false' }} }">
                        <button @click="submenuOpen = !submenuOpen"
                                class="flex items-center justify-between w-full px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                            <div class="flex items-center space-x-3">
                                <i class="{{ $item['icon'] }} w-5 text-gray-500"></i>
                                <span x-show="open">{{ $item['title'] }}</span>
                            </div>
                            <i x-show="open" class="fas" :class="submenuOpen ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                        </button>

                        <ul x-show="submenuOpen && open" x-transition class="pl-8 mt-2 space-y-2" x-cloak>
                            @foreach ($item['children'] as $child)
                                @php
                                    $hasRoute = isset($child['route']) && Route::has($child['route']);
                                    $link = $hasRoute ? route($child['route']) : '#';
                                @endphp

                                <li>
                                    <a href="{{ $link }}"
                                       class="block px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700
                                       {{ $hasRoute && request()->routeIs($child['route']) ? 'bg-gray-200 dark:bg-gray-700 font-bold' : '' }}">
                                        {{ $child['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>

                {{-- Menu thường --}}
                @else
                    @php
                        $hasRoute = isset($item['route']) && Route::has($item['route']);
                        $link = $hasRoute ? route($item['route']) : '#';
                    @endphp

                    @if (isset($item['logout']) && $item['logout'])
                        <li>
                            <form method="POST" action="{{ $link }}">
                                @csrf
                                <button class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-red-500 hover:text-white w-full text-left">
                                    <i class="{{ $item['icon'] }} w-5"></i>
                                    <span x-show="open">{{ $item['title'] }}</span>
                                </button>
                            </form>
                        </li>
                    @else
                        <li>
                            <a href="{{ $link }}"
                               class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700
                               {{ $hasRoute && request()->routeIs($item['route']) ? 'bg-gray-200 dark:bg-gray-700 font-bold' : '' }}">
                                <i class="{{ $item['icon'] }} w-5 text-gray-500"></i>
                                <span x-show="open">{{ $item['title'] }}</span>
                            </a>
                        </li>
                    @endif
                @endif

            @endforeach

        </ul>
    </div>
</aside>
