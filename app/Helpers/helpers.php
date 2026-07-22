<?php

use Carbon\Carbon;
use SebastianBergmann\Diff\Diff;
use Illuminate\Support\Str;
use App\Models\TipoUsuario;

use App\Models\Privilegio;
use Illuminate\Support\Facades\DB;

use App\Models\PermisosTipoUsuario;

function nombreUsuario($nom, $ape)
{
    $tok1 = explode(' ', $nom);
    $tok2 = explode(' ', $ape);

    // NOMBRE DEL USUARIO
    if (count($tok1) > 1) {
        if (strlen($tok1[0]) <= 3) {
            $nom = $tok1[0] . ' ' . $tok1[1];
        } else {
            $nom = $tok1[0];
        }
    } else {
        $nom = $tok1[0];
    }

    // APELLIDOS DEL USUARIO
    if (count($tok2) > 1) {
        if (strlen($tok2[0]) <= 3) {
            $ape = $tok2[0] . ' ' . $tok2[1];
        } else {
            $ape = $tok2[0];
        }
    } else {
        $ape = $tok2[0];
    }

    return $nom . ' ' . $ape;
}

function nombreUsuarioCorto($nombres)
{
    $token = explode(' ', mb_strtolower($nombres));
    return ucwords($token[0]);
}

function nombreUsuarioLargo($nombres, $apellidos)
{
    $token1 = explode(' ', mb_strtolower($nombres));
    $token2 = explode(' ', mb_strtolower($apellidos));

    return ucwords($token1[0]) . ' ' . ucwords($token2[0]);
}


function menuActivo($posicionMenu, $posicionActual)
{
    // SI LA POSICION ES INICIO
    if ($posicionActual == '') {
        $posicionActual = 'inicio';
    }
    // OTROS POSICICIONES
    $token = explode('.', $posicionMenu);

    $posicionMenu = $token[0];

    if ($posicionMenu == $posicionActual) {
        return true;
    }
    return false;
}

function mesNombreNumero($mes)
{
    $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    return $meses[$mes - 1];
}

function verificarFecha($fecha)
{
    $fechaActual = Carbon::now()->addDay(5);



    $fechaFin = Carbon::createFromFormat('Y-m-d', $fecha);

    // dd($fecha);

    if ($fechaActual >= $fechaFin) {
        $fecha = true;
    } else {
        $fecha = false;
    }

    return $fecha;
}

function limpiarFecha($f)
{
    $f = trim($f);
    $f = str_replace('"', '', $f);
    return preg_replace('/[^0-9\/]/', '', $f);
}


function numerosEnLetras($total)
{
    $divisores = [3, 6, 9];
    $unidades = ['', 'MIL', 'MILLONES'];
    $numeroLetra = '';

    $static = array(1 => 'UNO', 2 => 'DOS', 3 => 'TRES', 4 => 'CUATRO', 5 => 'CINCO', 6 => 'SEIS', 7 => 'SIETE', 8 => 'OCHO', 9 => 'NUEVE', 10 => 'DIEZ', 11 => 'ONCE', 12 => 'DOCE', 13 => 'TRECE', 14 => 'CATORCE', 15 => 'QUINCE', 16 => 'DIECISEIS', 17 => 'DIECISIETE', 18 => 'DIECIOCHO', 19 => 'DIECINUEVE', 20 => 'VEINTE', 30 => 'TREINTA', 40 => 'CUARENTA', 50 => 'CINCUENTA', 60 => 'SESENTA', 70 => 'SETENTA', 80 => 'OCHENTA', 90 => 'NOVENTA', 100 => 'CIEN', 500 => 'QUINIENTOS', 700 => 'SETECIENTOS', 900 => 'NOVECIENTOS', 1000 => 'MIL');

    // PARTE FLOTANTE
    $token = explode('.', $total);

    if (isset($token[1])) {
        $flotante = Str::padRight($token[1], 2, '0');
    } else {
        $flotante = '00';
    }

    // PARTE ENTERA
    $parteEntera = '';
    $tamanio = strlen($token[0]);

    for ($i = 0; $i < count($divisores); $i++) {
        if ($tamanio <= $divisores[$i]) {
            $parteEntera = Str::padLeft($token[0], $divisores[$i], '0');
            break;
        }
    }

    // SEPARA POR ESPACIOS
    $nuevoNro = '';

    for ($i = 0; $i < strlen($parteEntera); $i++) {
        $parte = $parteEntera[$i];

        if (($i + 1) % 3 == 0 && $i != 0) {
            $parte .= ' ';
        }

        $nuevoNro .= $parte;
    }

    $tokenUnidades = explode(' ', trim($nuevoNro));
    $cantUnidades = count($tokenUnidades) - 1;

    for ($j = 0; $j < count($tokenUnidades); $j++) {
        $letra = '';

        $entero = (int) $tokenUnidades[$j];
        $tam = strlen($entero);
        $newNum = $entero;

        if (array_key_exists($entero, $static)) {
            $letra = $static[$entero];
        } else {
            for ($i = 0; $i < $tam - 2; $i++) {
                $cero = str_repeat("0", $tam - 1 - $i);
                $dp = (int) "1" . $cero;

                $di = intval($newNum / $dp);
                if ($di != 0) {
                    if (array_key_exists($di * $dp, $static)) {
                        $letra = $letra . $static[$di * $dp] . " ";
                    } elseif (($di * $dp) % 100 == 0) {
                        $letra = $letra . $static[$di] . "CIENTOS ";
                    }
                }

                $newNum = $newNum % $dp;
            }

            $tam = strlen($newNum);

            if (trim($letra) == 'CIEN' && $newNum > 0) {
                $letra = "CIENTO ";
            }

            if ($tam <= 2) {
                if (array_key_exists($newNum, $static)) {
                    $letra = $letra . $static[$newNum];
                } else {
                    $div = intval($newNum / 10);
                    $res = $newNum % 10;

                    if ($div == 2) {
                        $letra = $letra . "VEINTI" . $static[$res];
                    } elseif ($div == 0) {
                        $letra = $letra;
                    } else {
                        $letra = $letra . $static[$div * 10]  . " Y " . $static[$res];
                    }
                }
            }
        }

        if ($cantUnidades > 0 && $letra == 'UNO') {
            $numeroLetra .= $numeroLetra . $unidades[$cantUnidades] . ' ';
        } else {
            $numeroLetra .= $letra . ' ' . $unidades[$cantUnidades]  . ' ';
        }

        $cantUnidades -= 1;
    }

    return trim($numeroLetra) . ' CON ' . $flotante . '/100 SOLES';
}

function formatoMoneda($valor)
{
    return number_format($valor, 2, '.', ' ');
}


function revisarMenu($menu, $id, $tipo)
{
    if ($tipo == 'tipo') {
        $tipoUsuario = TipoUsuario::find($id);
        $accesos = explode(',', $tipoUsuario->accesos);
        return in_array($menu, $accesos) ? true : false;
    } else {
        $privilegios = Privilegio::where([
            ['usuario_id', $id],
            ['menu_id', $menu],
        ])->first();

        return isset($privilegios->id) ? true : false;
    }
}

function revisarPermiso($permiso, $id, $tipo)
{
    if ($tipo == 'tipo') {
        $permisos = PermisosTipoUsuario::where([
            ['tipoUsuario_id', $id],
            ['permiso', $permiso]
        ])->first();
    } else {
        $permisos = DB::table('permissions')
            ->join('model_has_permissions', 'model_has_permissions.permission_id', 'permissions.id')
            ->select(
                'model_has_permissions.permission_id as id'
            )
            ->where([
                ['model_has_permissions.model_id', $id],
                ['permissions.name', $permiso]
            ])
            ->first();
    }

    return isset($permisos->id) ? true : false;
}
