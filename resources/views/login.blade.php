@extends('layouts.template')

@section('contentLogin')
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <!--begin::Logo-->
            <a href="index.html" class="d-block d-lg-none mx-auto py-20">
                <img alt="Logo" src="assets/media/logos/default.svg" class="theme-light-show h-25px" />
                <img alt="Logo" src="assets/media/logos/default-dark.svg" class="theme-dark-show h-25px" />
            </a>
            <div class="d-flex flex-column flex-column-fluid flex-center w-lg-50 p-10">
                <!--begin::Wrapper-->
                <div class="d-flex justify-content-between flex-column-fluid flex-column w-100 mw-450px">
                    <div class="d-flex flex-stack py-2">
                        <!--begin::Back link-->
                        <div class="me-2"></div>

                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="py-20">
                        <!--begin::Body-->
                        <div class="card-body">
                            <!--begin::Heading-->
                            <div class="text-start mb-10">
                                <!--begin::Title-->
                                <h1 class="text-gray-900 mb-3 fs-3x" data-kt-translate="sign-in-title">Tramite Documentario</h1>
                                <!--end::Title-->
                                <!--begin::Text-->
                                <div class="text-gray-500 fw-semibold fs-6" data-kt-translate="general-desc">Ingrese su Usuario y Contraseña</div>
                                <!--end::Link-->
                            </div>
                            @if (session('status'))
                                <div class="alert alert-danger">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <!--begin::Heading-->
                            <!--begin::Input group=-->
                            <form class="form w-100" method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="fv-row mb-8">
                                    <!--begin::Email-->
                                    <input type="text" placeholder="Usuario" name="nickname" autocomplete="off"
                                        class="form-control form-control-solid" value="{{ old('nickname') }}" />
                                </div>
                                <!--end::Input group=-->
                                <div class="fv-row mb-7">
                                    <input type="password" placeholder="Contraseña" name="password" autocomplete="off"
                                        class="form-control form-control-solid" value="{{ old('password') }}" />
                                </div>

                                <!--end::Wrapper-->
                                <!--begin::Actions-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Submit-->
                                    <button type="submit" class="btn btn-primary me-2 flex-shrink-0  btn_login">Ingresar</button>
                                </div>
                            </form>
                            <!--end::Actions-->
                        </div>
                        <!--begin::Body-->

                        <!--end::Form-->
                    </div>
                    <div class="m-0">
                    </div>
                </div>
            </div>
            <div class="d-none d-lg-flex flex-lg-row-fluid w-50 bgi-size-cover bgi-position-y-center bgi-position-x-start bgi-no-repeat"
                 style="background-image: url({{ url('storage/images/bg-3.jpg') }})"></div>
        </div>
    </div>
@endsection
