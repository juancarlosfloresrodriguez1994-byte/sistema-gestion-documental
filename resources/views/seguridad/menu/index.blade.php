@extends('layouts.template')

@section('titlePage', 'Menu | Ugel Alto Amazonas')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="relative">
            <div class="card card-custom gutter-b">
                <div class="card-header">
                    <div class="card-title">
                        <span class="card-icon">
                            <i class="flaticon2-settings text-primary"></i>
                        </span>
                        <h3 class="card-label text-primary">
                            Listado de Menus
                        </h3>
                    </div>

                    <div class="card-toolbar">
                        <a href="{{ route('menu.create') }}" class="btn btn-sm btn-primary font-weight-bold open-modal">
                            <i class="ki ki-plus"></i> &nbsp; Agregar
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @include('layouts.filtro')
                    @include('seguridad.menu.table')
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
