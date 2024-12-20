<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                @foreach ($menus as $menu)
                    @php
                    //dump($menu);
                        // Check if the current menu item is active
                        $isActive = request()->is(trim($menu->url, '/')) || request()->url() == url($menu->url);
                    @endphp

                    @if ($menu->children->isEmpty())
                        <!-- Single Menu Item -->
                        <a class="nav-link {{ $isActive ? 'active' : '' }}" href="{{ url('/')."/".$menu->url }}">
                            <div class="sb-nav-link-icon"><i class="fa {{ $menu->icon }}"></i></div>
                            {{ $menu->name }}
                        </a>
                    @else
                        <!-- Collapsible Menu Item -->
                        @php

                            // Check if any child menu is active
                            $isParentActive = collect($menu->children)->contains(function ($child) {
                                return request()->is(trim($child->url, '/')) || request()->url() == url($child->url);
                            });
                        @endphp
                        <a class="nav-link collapsed {{ ($isParentActive || $isActive) ? 'active' : '' }}" href="#" data-bs-toggle="collapse" data-bs-target="#menu-{{ $menu->id }}" aria-expanded="false" aria-controls="menu-{{ $menu->id }}">
                            <div class="sb-nav-link-icon"><i class="fa {{ $menu->icon }}"></i></div>
                            {{ $menu->name }}
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse {{ ($isParentActive || $isActive)? 'show' : '' }}" id="menu-{{ $menu->id }}" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link {{ request()->is(trim($menu->url, '/')) ? 'active' : '' }}" href="{{url('/')."/".$menu->url }}">
                                    <div class="sb-nav-link-icon"><i class="fa {{ $menu->icon }}"></i></div>
                                    {{ $menu->name }}
                                </a>
                                @foreach ($menu->children as $child)
                                    <a class="nav-link {{ request()->is(trim($child->url, '/')) ? 'active' : '' }}" href="{{url('/')."/".$child->url }}">
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
