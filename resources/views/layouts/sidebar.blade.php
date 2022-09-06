<aside class="main-sidebar sidebar-dark-info elevation-4">
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('public/upload/overdue.png') }}" alt="OverDue Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Over Due</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @foreach (config('menu.admin') as $menu)
                    @php
                        $active = false;
                        $currentUrl = Route::current()->getName();
                        $breadcrumb = '';
                        $active = array_search($currentUrl, $menu);
                        if ($currentUrl == $menu['route']) {
                            $breadcrumb .= $menu['title'];
                        }
                    @endphp
                    <li class="nav-item {{ $active === false ? '' : 'menu-open' }}">
                        <a href="{{ !empty($menu['route']) ? route($menu['route']) : '#' }}"
                            class="nav-link  {{ !empty($menu['route']) && str_contains($currentUrl, $menu['route']) ? 'active' : '' }}">
                            <i class="nav-icon {{ $menu['icon'] }}"></i>
                            <p>
                                {{ $menu['title'] }}
                            </p>
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
</aside>
