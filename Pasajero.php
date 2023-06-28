<?php
class Pasajero
{
    private $nombre;
    private $apellido;
    private $dni;
    private $telefono;
    private $objViaje;
    private $mensajeOperacion;

    /**
     ** Crea una instancia de la clase Pasajero
     */
    public function __construct()
    {
        $this->dni = 0;
        $this->nombre = '';
        $this->apellido = '';
        $this->objViaje = 0;
        $this->telefono = 0;
    }

    /**
     ** Función que asigna los valores ingresados por párametro
     ** a los atributos del Pasajero
     *  @param int $dniCargar
     *  @param string $nombreCargar
     *  @param string $apellidoCargar
     *  @param mixed $objViaje
     *  @param string $telefonoCargar
     */
    public function cargar($dniCargar, $nombreCargar, $apellidoCargar, $objViaje, $telefonoCargar)
    {
        $this->dni = $dniCargar;
        $this->nombre = $nombreCargar;
        $this->apellido = $apellidoCargar;
        $this->objViaje = $objViaje;
        $this->telefono = $telefonoCargar;
    }

    //?                 ╔════════════════════════════════════════════════════════════════════════════╗
    //?                 ║                             METODOS DE ACCESO                              ║
    //?                 ╚════════════════════════════════════════════════════════════════════════════╝

    public function setDni($dniNuevo)
    {
        $this->dni = $dniNuevo;
    }
    public function getDni()
    {
        return $this->dni;
    }
    public function setNombre($nombreNuevo)
    {
        $this->nombre = $nombreNuevo;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function setApellido($apellidoNuevo)
    {
        $this->apellido = $apellidoNuevo;
    }
    public function getApellido()
    {
        return $this->apellido;
    }
    public function setTelefono($nuevoTel)
    {
        $this->telefono = $nuevoTel;
    }
    public function getTelefono()
    {
        return $this->telefono;
    }
    public function setObjViaje($nuevoObjViaje)
    {
        $this->objViaje = $nuevoObjViaje;
    }
    public function getObjViaje()
    {
        return $this->objViaje;
    }
    public function setMensajeOperacion($nuevoMensaje)
    {
        $this->mensajeOperacion = $nuevoMensaje;
    }
    public function getMensajeOperacion()
    {
        return $this->mensajeOperacion;
    }

    //?                 ╔════════════════════════════════════════════════════════════════════════════╗
    //?                 ║                           METODOS PROPIOS DE CLASE                         ║
    //?                 ╚════════════════════════════════════════════════════════════════════════════╝

    /**
     ** Devuelve un string que contiene toda la información del estado de una instancia de tipo Pasajero
     * @return string
     */
    public function __toString()
    {
        $mensaje = " Nombre: " . $this->getNombre() . " ║ DNI: " . $this->getDni() . " \n";
        $mensaje .= " Apellido: " . $this->getApellido() . " ║ Telefono: " . $this->getTelefono() . "\n";
        $mensaje .= " Viaje n° : " . $this->getObjViaje()->getCodViaje() . "\n";
        return $mensaje;
    }

    //?                 ╔════════════════════════════════════════════════════════════════════════════╗
    //?                 ║                   METODOS RELACIONADOS A LA BASE DE DATOS                  ║
    //?                 ╚════════════════════════════════════════════════════════════════════════════╝

    /**
     ** Recupera los datos de un pasajero en la base de datos a partir de un documento ingresado
     ** y los setea al objeto pasajero actual
     ** Retorna true si tiene éxito en la operación, false en caso contrario
     *  @param string $dni
     *  @return boolean
     */
    public function buscar($dni)
    {
        $base = new BaseDatos();
        $consultaPasajero = "Select * from pasajero where pdocumento=" . $dni;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaPasajero)) {
                if ($row2 = $base->Registro()) {
                    $objViaje = new Viaje();
                    $objViaje->buscar($row2['idviaje']);
                    $this->cargar($dni, $row2['pnombre'], $row2['papellido'], $objViaje, $row2['ptelefono']);
                    $resp = true;
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }

    /**
     ** Inserta un nuevo pasajero a la base de datos según los datos actuales almacenados
     ** en los atributos del objeto Pasajero.
     ** Retorna true si tiene éxito en la operación, false en caso contrario
     *  @return boolean
     */
    public function insertar()
    {
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO pasajero(pdocumento, pnombre, papellido, ptelefono, idviaje)
				VALUES (" . $this->getDni() . ",'" . $this->getNombre() . "','" . $this->getApellido() . "'," . $this->getTelefono() . ",'" . $this->getObjViaje()->getCodViaje() . "')";

        if ($base->Iniciar()) {

            if ($base->Ejecutar($consultaInsertar)) {

                $resp = true;

            } else {
                $this->setMensajeOperacion($base->getError());

            }

        } else {
            $this->setMensajeOperacion($base->getError());

        }
        return $resp;
    }

    /**
     ** Busca todos los pasajeros que cumplan una condición y devuelve un arreglo
     ** que los contiene.
     *  @param string $condicion
     *  @return array
     */
    public function listar($condicion = "")
    {
        $arregloPasajeros = null;
        $base = new BaseDatos();
        $consultaPasajeros = "Select * from pasajero ";
        if ($condicion != "") {
            $consultaPasajeros = $consultaPasajeros . ' where ' . $condicion;
        }
        $consultaPasajeros .= " order by papellido ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaPasajeros)) {
                $arregloPasajeros = array();
                while ($row2 = $base->Registro()) {
                    $nrodoc = $row2['pdocumento'];
                    $nombre = $row2['pnombre'];
                    $apellido = $row2['papellido'];
                    $telefono = $row2['ptelefono'];
                    $objViaje = new Viaje();
                    $objViaje->buscar($row2['idviaje']);
                    $pasaj = new Pasajero();
                    $pasaj->cargar($nrodoc, $nombre, $apellido, $objViaje, $telefono);
                    array_push($arregloPasajeros, $pasaj);
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $arregloPasajeros;
    }

    /**
     ** Modifica todos los campos del pasajero actual (identificado por su documento)
     ** en la base de datos según el estado actual de todos sus atributos.
     ** No se permite actualizar el número de ticket
     ** Retorna true si tiene éxito en la operación, false en caso contrario
     *  @return boolean
     */
    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $consultaModifica = "UPDATE pasajero SET pnombre='" . $this->getNombre() . "',papellido='" . $this->getApellido() . "'
                           ,ptelefono=" . $this->getTelefono() . ",idviaje=" . $this->getObjViaje()->getCodViaje() . " WHERE pdocumento =" . $this->getDni();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaModifica)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }

    /**
     ** Elimina un Pasajero de la base de datos según su documento
     ** Lee el documento del Pasajero actual y lo envía en la consulta.
     ** Retorna true si tiene éxito en la operación, false en caso contrario
     * @return boolean
     */
    public function eliminar()
    {
        $base = new BaseDatos();
        $resp = false;
        if ($base->Iniciar()) {
            $consultaBorra = "DELETE FROM pasajero WHERE pdocumento=" . $this->getDni();
            if ($base->Ejecutar($consultaBorra)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }
}
