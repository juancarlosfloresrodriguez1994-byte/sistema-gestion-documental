<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <!--begin::Toolbar container-->
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <!--begin::Title-->
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                @isset($titlePage)
                    {{ $titlePage }}
                @endisset()</h1>
            <!--end::Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <!--begin::Item
                
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('inicio') }}" class="text-muted text-hover-primary">Inicio</a>
                </li>-->
                <!--end::Item-->
                <!--begin::Item
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                </li>-->
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item text-muted">
                    @isset($titlePage)
                        {{ $titlePage }}
                    @endisset()
                </li>
                <!--end::Item-->
            </ul>
            <!--end::Breadcrumb-->
        </div>

    </div>
</div>
