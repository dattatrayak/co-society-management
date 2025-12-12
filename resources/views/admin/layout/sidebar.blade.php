<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                @php
                    $segments = explode('/', rtrim(request()->url(), '/'));
                    $lastWord = end($segments);
                    $urlLast = $lastWord == 'create' || $lastWord == 'edit' ? '/' . $lastWord : '';

                @endphp
                @foreach ($menus as $menu)
                    @php
                        //dump($menu);
                        // Check if the current menu item is active
                        $isActive =
                            request()->is(trim($menu->url, '/')) ||
                            request()->is(trim($menu->url, '/') . '/*/edit') ||
                            request()->is(trim($menu->url, '/') . '/create');
                    @endphp

                    @if ($menu->children->isEmpty())
                        <!-- Single Menu Item -->
                        <a class="nav-link {{ $isActive ? 'active' : '' }}" href="{{ url('/') . '/' . $menu->url }}">
                            <div class="sb-nav-link-icon"><i class="fa {{ $menu->icon }}"></i></div>
                            {{ $menu->name }}
                        </a>
                    @else
                        <!-- Collapsible Menu Item -->
                        @php

                            // Check if any child menu is active
                            $isParentActive = collect($menu->children)->contains(function ($child) {
                                // return request()->is(trim($child->url, '/')) || request()->url() == url($child->url.$urlLast);
                                return request()->is(trim($child->url, '/')) ||
                                    request()->is(trim($child->url, '/') . '/*/edit') || // Match URLs like `society/flat/1/edit`
                                    request()->is(trim($child->url, '/') . '/create'); // Match URLs like `society/flat/create`
                            });
                        @endphp
                        <a class="nav-link collapsed {{ $isParentActive || $isActive ? 'active' : '' }}" href="#"
                            data-bs-toggle="collapse" data-bs-target="#menu-{{ $menu->id }}" aria-expanded="false"
                            aria-controls="menu-{{ $menu->id }}">
                            <div class="sb-nav-link-icon"><i class="fa {{ $menu->icon }}"></i></div>
                            {{ $menu->name }}
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse {{ $isParentActive || $isActive ? 'show' : '' }}"
                            id="menu-{{ $menu->id }}" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link {{ request()->is(trim($menu->url, '/')) ||
                                request()->is(trim($menu->url, '/') . '/*/edit') ||
                                request()->is(trim($menu->url, '/') . '/create')
                                    ? 'active'
                                    : '' }}"
                                    href="{{ url('/') . '/' . $menu->url }}">
                                    <div class="sb-nav-link-icon"><i class="fa {{ $menu->icon }}"></i></div>
                                    {{ $menu->name }}
                                </a>
                                @foreach ($menu->children as $child)
                                    <a class="nav-link {{ request()->is(trim($child->url, '/')) ||
                                    request()->is(trim($child->url, '/') . '/*/edit') ||
                                    request()->is(trim($child->url, '/') . '/create')
                                        ? 'active'
                                        : '' }}"
                                        href="{{ url('/') . '/' . $child->url }}">
                                        <div class="sb-nav-link-icon"><i class="fa {{ $child->icon }}"></i></div>
                                        {{ $child->name }}
                                    </a>
                                @endforeach
                            </nav>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>


    </nav>
</div>
