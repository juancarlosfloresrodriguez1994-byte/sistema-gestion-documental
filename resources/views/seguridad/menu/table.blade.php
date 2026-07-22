<div class="table-responsive">
    <table class="table table-border">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Menu</th>
                <th>Url</th>
                <th>Icono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($datos as $menu)
                <tr>
                    <td>{{ $loop->iteration + $datos->firstItem() - 1 }}</td>
                    <td>{{ $menu->descripcion }}</td>
                    <td>{{ $menu->url }}</td>
                    <td>{{ $menu->icono }}</td>
                    <td>
                        <form action="{{ route('menu.destroy', $menu->id) }}" method="POST"
                            class="form-delete">
                            @csrf
                            @method('DELETE')

                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('menu.edit', $menu->id) }}"
                                    class="btn btn-light-primary"><i class="flaticon2-edit"></i></a>
                                <button type="submit" class="btn btn-light-danger btn-eliminar"><i
                                        class="flaticon2-trash"></i></button>
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

