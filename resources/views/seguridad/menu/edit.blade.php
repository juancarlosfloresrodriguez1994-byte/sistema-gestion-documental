@extends('layouts.template')

@section('titlePage', 'Crear Menu | Ugel')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-custom gutter-b">
                <div class="card-header">
                    <div class="card-title">
                        <span class="card-icon">
                            <i class="flaticon2-settings text-primary"></i>
                        </span>
                        <h3 class="card-label text-primary">
                            Agregar Menu
                        </h3>
                    </div>
                </div>

                <form action="{{ route('menu.update', $menu->id) }}" method="POST" class="fv-plugins-bootstrap">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="descripcion">Descripcion</label>
                                    <input type="text" id="descripcion" name="descripcion"
                                        class="form-control @error('descripcion') is-invalid @enderror" autocomplete="off"
                                        value="{{ $menu->descripcion }}" autofocus />

                                    @error('descripcion')
                                        <div class="invalid-feedback">{!! $message !!}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="descripcion">Url</label>
                                    <input type="text" id="url" name="url"
                                        class="form-control @error('url') is-invalid @enderror" autocomplete="off"
                                        value="{{ $menu->url }}" autofocus />

                                    @error('url')
                                        <div class="invalid-feedback">{!! $message !!}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="descripcion">Icono</label>
                                    <input type="text" id="icono" name="icono"
                                        class="form-control @error('url') is-invalid @enderror" autocomplete="off"
                                        value="{{ $menu->icono }}" autofocus />

                                    @error('url')
                                        <div class="invalid-feedback">{!! $message !!}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="descripcion">Padre</label>
                                    <input type="text" id="padre" name="padre"
                                        class="form-control @error('padre') is-invalid @enderror" autocomplete="off"
                                        value="{{ $menu->padre }}" autofocus />

                                    @error('padre')
                                        <div class="invalid-feedback">{!! $message !!}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="descripcion">open</label>
                                    <select name="open" id="open"
                                        class="form-control tramite  validate_modal">
                                        <option value="NULL">NINGUNO</option>
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="descripcion">orden</label>
                                    <input type="text" id="orden" name="orden"
                                        class="form-control @error('orden') is-invalid @enderror" autocomplete="off"
                                        value="{{ $menu->orden }}" autofocus />

                                    @error('orden')
                                        <div class="invalid-feedback">{!! $message !!}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary mr-2" id="btn-general"><i class="far fa-save"></i>
                            &nbsp; Agregar</button>
                        <a href="{{ route('menu.index') }}" class="btn btn-danger"><i class="fas fa-reply"></i>
                            &nbsp; Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>



@endsection
