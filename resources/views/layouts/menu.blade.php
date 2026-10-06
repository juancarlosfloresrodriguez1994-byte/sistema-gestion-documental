<div class="app-sidebar-menu overflow-hidden flex-column-fluid">

    <!--begin::Menu wrapper-->
    <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">

        <!--begin::Scroll wrapper-->
        <div id="kt_app_sidebar_menu_scroll"
            class="scroll-y my-5 mx-3"
            data-kt-scroll="true"
            data-kt-scroll-activate="true"
            data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
            data-kt-scroll-wrappers="#kt_app_sidebar_menu"
            data-kt-scroll-offset="5px"
            data-kt-scroll-save-state="true">

            {{-- Sección label --}}
            <div class="menu-label-section px-3 mb-3">
                <span class="menu-section-label">NAVEGACIÓN</span>
            </div>

            <!--begin::Menu-->
            <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6"
                id="kt_app_sidebar_menu"
                data-kt-menu="true"
                data-kt-menu-expand="false">

                @foreach ($menuAll as $menu)

                    @php
                        $submenuRoutes = collect($menu['submenu'] ?? [])
                            ->pluck('url')
                            ->filter()
                            ->all();

                        $parentActive = !empty($submenuRoutes)
                            && request()->routeIs($submenuRoutes);

                        $singleRouteName = $menu['url'] ?? null;

                        $singleActive = $singleRouteName
                            && request()->routeIs($singleRouteName);
                    @endphp

                    {{-- MENU CON SUBMENU --}}
                    @if ($menu['open'] == 1 && !empty($menu['submenu']))

                        <div data-kt-menu-trigger="click"
                            class="menu-item menu-accordion {{ $parentActive ? 'show' : '' }}">

                            <span class="menu-link menu-link-custom">

                                <span class="menu-icon">
                                    <i class="{{ $menu['icono'] }}">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </span>

                                <span class="menu-title">
                                    {{ $menu['descripcion'] }}
                                </span>

                                <span class="menu-arrow"></span>

                            </span>

                            <!--begin::Menu sub-->
                            <div class="menu-sub menu-sub-accordion">

                                @foreach ($menu['submenu'] as $submenu)

                                    <div class="menu-item">

                                        <a href="{{ route($submenu->url) }}"
                                            class="menu-link {{ request()->routeIs($submenu->url) ? 'active' : '' }}">

                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>

                                            <span class="menu-title">
                                                {{ $submenu->descripcion }}
                                            </span>

                                        </a>

                                    </div>

                                @endforeach

                            </div>
                            <!--end::Menu sub-->

                        </div>

                    @else

                        {{-- MENU SIMPLE --}}
                        @if (!empty($menu['url']))

                            <div class="menu-item">

                                <a href="{{ route($menu['url']) }}"
                                    class="menu-link menu-link-custom {{ $singleActive ? 'active' : '' }}">

                                    <span class="menu-icon">
                                        <i class="{{ $menu['icono'] }}">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </span>

                                    <span class="menu-title">
                                        {{ $menu['descripcion'] }}
                                    </span>

                                </a>

                            </div>

                        @endif

                    @endif

                @endforeach

            </div>
            <!--end::Menu-->

        </div>
        <!--end::Scroll wrapper-->

    </div>
    <!--end::Menu wrapper-->

</div>