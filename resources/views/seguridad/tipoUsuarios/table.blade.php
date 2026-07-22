<div class="table-responsive">
    <table class="table table-border">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Descripcion</th>
                <th>Operaciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($datos as $TipoUsuario)
            <tr>
                <td>{{ $loop->iteration + $datos->firstItem() - 1 }}</td>
                <td>{{ $TipoUsuario->descripcion}}</td>
                <td>
                    <form action="{{ route('tipo-usuario.destroy', $TipoUsuario->id) }}" method="POST" class="form-delete">
                        @csrf
                        @method('DELETE')

                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('tipo-usuario.edit', $TipoUsuario->id) }}" class="btn btn-light-success"><i class="flaticon2-edit"></i></a>

                        </div>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">No se encontró datos</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
