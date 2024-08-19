<nav class="main-sidebar ps-menu">
    <div class="sidebar-header">
        <div class="text border-bottom">POLAR</div>
        <img src="path_to_image.png" alt="Lab Sentral UNPAD" class="img-logo d-none">
        <div class="close-sidebar action-toggle">
            <i class="ti-close"></i>
        </div>
    </div>
    <div class="sidebar-content">
        <ul>
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="link">
                    <i class="fa-solid fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="menu-category">
                <span class="text-uppercase">User Interface</span>
            </li>

            @foreach (getMenus() as $menu)
                @if(auth()->user()->can('read '. $menu->permission))
                {{-- @can('read '. $menu->permission) --}}
                    @if ($menu->type_menu == 'parent')
                        @php
                            $currentSegment = request()->segment(2);
                            $isActive = getParentMenus($currentSegment) == $menu->name;
                            $class = $isActive ? 'active open' : '';
                        @endphp
                        <li class="{{ $class }}">
                            <a
                                href="#" class="main-menu has-dropdown">
                                <i class="{{ $menu->icon }}"></i>
                                <span class="text-capitalize">{{ $menu->name }}</span>
                            </a>
                            <ul class="sub-menu {{ getParentMenus(request()->segment(2)) == $menu->name ? 'expand' : '' }}">
                                @foreach ($menu->subMenus as $submenu)
                                    @can('read '. $menu->permission)
                                        <li
                                            class="{{ request()->segment(2) == explode('/', $submenu->url)[1] ? 'active' : '' }}">
                                            <a href="{{ url($submenu->url) }}" class="link">
                                                <span class="text-capitalize">
                                                    {{ $submenu->name }}
                                                </span>
                                            </a>
                                        </li>
                                    @endcan
                                @endforeach
                            </ul>
                        </li>
                    @elseif ($menu->type_menu == 'single')
                        @php
                            $menuUrlSegments = explode('/', $menu->url);
                            $menuSegment = count($menuUrlSegments) > 1 ? $menuUrlSegments[1] : $menuUrlSegments[0];
                            $isActive = request()->segment(2) == $menuSegment;
                        @endphp
                        <li class="{{ $isActive ? 'active' : '' }}">
                            <a href="{{ url($menu->url) }}" class="link">
                                <i class="{{ $menu->icon }}"></i>
                                <span class="text-capitalize">{{ $menu->name }}</span>
                            </a>
                        </li>
                    @endif
                {{-- @endcan --}}
                @endif
            @endforeach
        </ul>
    </div>
</nav>
