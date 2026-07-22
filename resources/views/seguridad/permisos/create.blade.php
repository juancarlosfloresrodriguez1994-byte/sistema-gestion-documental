@extends('layouts.template')

@section('titlePage', 'Permisos | Ugel')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-custom gutter-b">
                <div class="card-header">
                    <div class="card-title">
                        <span class="card-icon">
                            <i class="flaticon2-contract text-primary"></i>
                        </span>
                        <h3 class="card-label text-primary">
                            Agregar permisos - 
                        </h3>
                    </div>
                </div>

                <form action="{{ route('permi.store') }}" method="POST" class="fv-plugins-bootstrap">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="descripcion">Descripcion</label>
                                    <input type="text" id="descripcion" name="descripcion"
                                        class="form-control @error('descripcion') is-invalid @enderror" autocomplete="off"
                                        value="{{ old('descripcion') }}" autofocus />

                                    @error('descripcion')
                                        <div class="invalid-feedback">{!! $message !!}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="descripcion">Name</label>
                                    <input type="text" id="name" name="name"
                                        class="form-control @error('name') is-invalid @enderror" autocomplete="off"
                                        value="{{ old('name') }}" autofocus />

                                    @error('name')
                                        <div class="invalid-feedback">{!! $message !!}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="descripcion">Menu</label>
                                    <select name="reso_Asunto" id="kt_select2_1" class="form-control validate_modal">
                                        <option value="">[ ASUNTOS ]</option>
                                        @foreach ($menu as $menu)
                                            <option value="{{ $menu->id }}">{{ $menu->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('descripcion')
                                        <div class="invalid-feedback">{!! $message !!}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="descripcion">Orden</label>
                                    <input type="text" id="name" name="name"
                                        class="form-control @error('name') is-invalid @enderror" autocomplete="off"
                                        value="{{ old('name') }}" autofocus />
                                    @error('name')
                                        <div class="invalid-feedback">{!! $message !!}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary mr-2" id="btn-general"><i class="far fa-save"></i>
                            &nbsp; Agregar</button>
                        <a href="{{ route('permi.index') }}" class="btn btn-danger"><i class="fas fa-reply"></i>
                            &nbsp; Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>



@endsection
