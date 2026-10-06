<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
    <!--begin::Page-->
    <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
        <!--begin::Header-->
        <div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: true, lg: true}"
            data-kt-sticky-name="app-header-minimize" data-kt-sticky-offset="{default: '200px', lg: '0'}"
            data-kt-sticky-animation="false" style="background-color: #0073B7">
            <!--begin::Header container-->
            <div class="app-container container-fluid d-flex align-items-stretch justify-content-between"
                id="kt_app_header_container">
                <!--begin::Sidebar mobile toggle-->
                <div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
                    <div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
                        <i class="ki-duotone ki-abstract-14 fs-2 fs-md-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>

                <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
                    <a href="{{ route('inicio') }}" class="d-lg-none">
                        <img alt="Logo" src="{{ url('storage/images/LOGO_UGELAA.png') }}" class="h-30px" />
                    </a>
                </div>

                @include('layouts.user')
                <!--end::Header wrapper-->
            </div>
            <!--end::Header container-->
        </div>
        <!--end::Header-->
        <!--begin::Wrapper-->
        <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
            <!--begin::Sidebar-->
            <div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true"
                data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}"
                data-kt-drawer-overlay="true" data-kt-drawer-width="260px" data-kt-drawer-direction="start"
                data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">

                <!--begin::Logo-->
                <div class="app-sidebar-logo px-6 d-flex align-items-center justify-content-between" id="kt_app_sidebar_logo">
                    <!--begin::Logo image-->
                    <a href="{{ route('inicio') }}" class="d-flex align-items-center gap-3 sidebar-logo-link">
                        <img alt="Logo" src="{{ url('storage/images/LOGO_UGELAA.png') }}"
                            class="sidebar-logo-img app-sidebar-logo-default" />
                        <img alt="Logo" src="{{ url('storage/images/LOGO_UGELAA.png') }}"
                            class="sidebar-logo-img-min app-sidebar-logo-minimize" />
                    </a>

                    <div id="kt_app_sidebar_toggle"
                        class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
                        data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
                        data-kt-toggle-name="app-sidebar-minimize">
                        <i class="ki-duotone ki-black-left-line fs-3 rotate-180">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>

                </div>
                <!--end::Logo-->

                <!--begin::sidebar menu-->
                @include('layouts.menu')

            </div>

            <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                <!--begin::Content wrapper-->
                <div class="d-flex flex-column flex-column-fluid">
                    <!--begin::Toolbar-->
                    @include('layouts.container')
                    @yield('content')
                </div>
                <!--end::Content wrapper-->

                <!--begin::Footer-->
                @include('layouts.footer')
                <!--end::Footer-->
            </div>
            <!--end:::Main-->
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Page-->
</div>
