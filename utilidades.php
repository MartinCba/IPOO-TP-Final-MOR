<?php
/**
 ** Verifica que el dni ingresado sea de 7 u 8 dígitos.
 * @param int $dni
 * @return boolean $valido
 */
function verificaFormatoDNI($dni)
{
    if ($dni < 99999 || $dni > 99999999) {
        $valido = false;
    } else {
        $valido = true;
    }
    return !$valido;
}

/**
 ** Verifica que se ingrese algo y no quede vacío
 * @param string $stringAMostrar
 * @return $dato
 */
function verificaIngreso($stringAMostrar)
{
    do {
        echo $stringAMostrar;
        $dato = trim(fgets(STDIN));
    } while (empty($dato));
    return $dato;
}

/**
 ** Verifica que se ingrese algo y no quede vacío
 *  @param string $stringAMostrar
 *  @return $dato
 */
function verificaIngresoInt($stringAMostrar)
{
    do {
        echo $stringAMostrar;
        $dato = trim(fgets(STDIN));
    } while (empty($dato) || !is_numeric($dato));
    return $dato;
}

/**
 ** Verifica que se ingrese un número dentro de un rango especificado por el minimo y maximo (parámetros)
 *  @param string $stringAMostrar
 *  @param int $min
 *  @param int $max
 *  @return $respuesta
 */
function verificaIngresoNumerico($stringAMostrar, $min, $max)
{
    do {
        echo $stringAMostrar;
        $respuesta = trim(fgets(STDIN));
    } while (($respuesta < $min || $respuesta > $max) || !is_numeric($respuesta));
    return $respuesta;
}

/**
 ** Esta función se encarga de que el DNI a cargar no se encuentre ya en el arreglo
 *   @param array $pasajeros son los pasajeros ya cargados
 *   @return int $dniPasajero
 */
function verificaDNI($pasajeros)
{
    do {
        $ciclo = false;
        echo 'Ingrese el DNI del pasajero: ' . "\n";
        $dniPasajero = trim(fgets(STDIN));
        if (!is_int($dniPasajero)) {
            //Verifica si se ingresó un dato de 7 u 8 numeros
            if (verificaFormatoDNI($dniPasajero)) {
                echo 'El DNI ingresado es invalido, intente nuevamente' . "\n";
                $ciclo = true;
            } else {
                //Verifica si el pasajero ya fueron pedidos por consola pero no ingresados todavía, para no repetir registros
                if (is_array($pasajeros) && !empty($pasajeros)) {
                    $existe = recorrePasajeros($dniPasajero, $pasajeros);
                    if ($existe) {
                        echo 'El DNI ingresado ya se encontraba cargado, intente con otro' . "\n";
                        $ciclo = true;
                    }
                }
            }
        } else {
            echo 'No se ingresó un DNI valido, intente nuevamente' . "\n";
            $ciclo = true;
        }
    } while ($ciclo);
    return $dniPasajero;
}

/**
 ** Esta función se encarga de que el DNI a seleccionar se encuentre ya en el arreglo
 *  @param array $pasajeros son los pasajeros ya cargados
 *  @return int $dniPasajero
 */
function verificaDNIYaIngresado($pasajeros)
{
    do {
        $ciclo = true;
        echo 'Ingrese el DNI del pasajero: ' . "\n";
        $dniPasajero = trim(fgets(STDIN));
        if (!is_int($dniPasajero)) {
            //Verifica si se ingresó un dato de 7 u 8 numeros
            if (verificaFormatoDNI($dniPasajero)) {
                echo 'El DNI ingresado es invalido, intente nuevamente' . "\n";
                $ciclo = true;
            } else {
                //Verifica si el pasajero se encuentra cargado
                if (is_array($pasajeros) && !empty($pasajeros)) {
                    $existe = recorrePasajeros($dniPasajero, $pasajeros);
                    if ($existe) {
                        $ciclo = false;
                    }
                }
            }
        } else {
            echo 'No se ingresó un DNI valido, intente nuevamente' . "\n";
            $ciclo = true;
        }
    } while ($ciclo);
    return $dniPasajero;
}

/**
 ** Esta función es para recorrer los pasajeros
 *  @param array $dnIsPasajeros
 *  @param array $pasajeros
 *  @return bool
 */
function recorrePasajeros($dniPasajero, $pasajeros)
{
    $i = 0;
    $encontro = false;
    while ($i < count($pasajeros) && !$encontro) {
        $encontro = $pasajeros[$i]->getDni() == $dniPasajero;
        $i++;
    }
    return $encontro;
}
/**
 ** Esta función se encarga de preguntarle al usuario si quiere ingresar
 ** otro pasajero y para cuando recibe una respuesta valida.
 *  @return boolean $ingresoUsuario
 */
function pregunta()
{

    $ciclo = true;
    do {
        echo 'Desea ingresar otro pasajero? ingrese si o no' . "\n";
        $respuesta = trim(fgets(STDIN));
        $respuesta = strtolower($respuesta);

        if ($respuesta == 'si' || $respuesta == 'no') {
            $ciclo = false;
            $ingresoUsuario = $respuesta == 'si' ? true : false;
        } else {
            echo 'Ingreso una respuesta incorrecta, intente nuevamente.' . "\n";
        }
    } while ($ciclo);
    return $ingresoUsuario;
}
