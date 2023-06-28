<?php
class Viaje
{
    private $cod_viaje;
    private $destino;
    private $cantMaximaPasajeros;
    private $pasajeros;
    private $responsable;
    private $costo;
    private $sumCosto;
    private $empresa;
    private $mensajeOperacion;

    /**
     ** Crea una instancia de la clase Viaje
     */
    public function __construct()
    {
        $this->cod_viaje = 0;
        $this->destino = '';
        $this->cantMaximaPasajeros = 0;
        $this->pasajeros = 0;
        $this->responsable = 0;
        $this->costo = 0;
        $this->empresa = 0;
    }

    /**
     ** Función que asigna los valores ingresados por párametro
     ** a los atributos del Viaje.
     * @param int $codViaje
     * @param string $des
     * @param int $cantmaxpasajeros
     * @param array $pas
     * @param ResponsableV $res
     * @param Empresa $emp
     * @param int $cos
     */
    public function cargar($codViaje, $des, $cantmaxpasajeros, $pas, $res, $cos, $emp)
    {
        $this->cod_viaje = $codViaje;
        $this->destino = $des;
        $this->cantMaximaPasajeros = $cantmaxpasajeros;
        $this->pasajeros = $pas;
        $this->responsable = $res;
        $this->costo = $cos;
        $this->empresa = $emp;
    }

    //?                 ╔════════════════════════════════════════════════════════════════════════════╗
    //?                 ║                             METODOS DE ACCESO                              ║
    //?                 ╚════════════════════════════════════════════════════════════════════════════╝

    public function setCodViaje($codviaje)
    {
        $this->cod_viaje = $codviaje;
    }
    public function getCodViaje()
    {
        return $this->cod_viaje;
    }
    public function setDestino($des)
    {
        $this->destino = $des;
    }
    public function getDestino()
    {
        return $this->destino;
    }
    public function setCantMaximaPasajeros($cantMaxPasajeros)
    {
        $this->cantMaximaPasajeros = $cantMaxPasajeros;
    }
    public function getCantMaximaPasajeros()
    {
        return $this->cantMaximaPasajeros;
    }
    public function setPasajeros($pasajeros)
    {
        $this->pasajeros = $pasajeros;
    }
    public function getPasajeros()
    {
        return $this->pasajeros;
    }
    public function setResponsable($res)
    {
        $this->responsable = $res;
    }
    public function getResponsable()
    {
        return $this->responsable;
    }
    public function setCosto($costo)
    {
        $this->costo = $costo;
    }
    public function getCosto()
    {
        return $this->costo;
    }
    public function setSumCosto($sumCosto)
    {
        $this->sumCosto = $sumCosto;
    }
    public function getSumCosto()
    {
        return $this->sumCosto;
    }
    public function setEmpresa($nuevoEmp)
    {
        $this->empresa = $nuevoEmp;
    }
    public function getEmpresa()
    {
        return $this->empresa;
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
     ** Devuelve un string con la información de todos los pasajeros del viaje
     *  @return string
     */
    public function mostrarPasajeros()
    {
        $mensaje = '';
        $colPasajeros = $this->getPasajeros();
        if (count($colPasajeros) > 0) {
            for ($i = 0; $i < count($colPasajeros); $i++) {
                $mensaje .= "╔════════════════════════════════════════════════════════════╗\n"
                    . "║                         PASAJERO n° " . ($i + 1) . "                      ║\n"
                    . "╚════════════════════════════════════════════════════════════╝\n" .
                    $colPasajeros[$i] . "\n";
            }
        }
        return $mensaje;
    }

    /**
     ** Devuelve un string con todos los elementos que componen al viaje
     *  @return string
     */
    public function __toString()
    {
        $mp = $this->mostrarPasajeros();
        $mensaje = "╔════════════════════════════════════════════════════════════╗\n";
        $mensaje .= "║                           VIAJE                            ║\n";
        $mensaje .= "╚════════════════════════════════════════════════════════════╝\n";
        $mensaje .= "  ID de viaje: " . $this->getCodViaje() . " ║ Destino: " . $this->getDestino() . " ║ Costo: " . $this->getCosto() . " \n";
        $mensaje .= "  Cantidad máxima de pasajeros: " . $this->getCantMaximaPasajeros() . " ║ Suma de costos: " . $this->getSumCosto() . "\n";
        $mensaje .= "" . $this->getEmpresa() . "\n" . $mp;
        return $mensaje;
    }

    /**
     ** Este método se encarga de traer los datos de la bd de los pasajeros relacionados con el viaje
     ** y setearlos en el atributo pasajeros.
     */
    public function traePasajeros()
    {
        $pasajero = new Pasajero();
        $condicion = "idviaje =" . $this->getCodViaje();
        $colPasajeros = $pasajero->listar($condicion);
        $this->setPasajeros($colPasajeros);
    }

    /**
     ** Este método se encarga de vender un pasaje.
     */
    public function venderPasaje()
    {
        $cos = $this->getCosto();
        $sumCosto = $this->getSumCosto();

        $sumCosto = $sumCosto + intval($cos);
        $this->setSumCosto($sumCosto);
    }

    //?                 ╔════════════════════════════════════════════════════════════════════════════╗
    //?                 ║                   METODOS RELACIONADOS A LA BASE DE DATOS                  ║
    //?                 ╚════════════════════════════════════════════════════════════════════════════╝

    /**
     ** Recupera los datos de un viaje en la base de datos a partir de un código de viaje ingresado
     ** y los setea al objeto viaje actual.
     ** Retorna true si tiene éxito en la operación, false en caso contrario
     *  @param int $codviaje
     *  @return boolean
     */
    public function buscar($codviaje)
    {
        $base = new BaseDatos();
        $consultaViaje = "Select * from viaje where idviaje=" . $codviaje;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaViaje)) {
                if ($row2 = $base->Registro()) {
                    $objEmpresa = new Empresa();
                    $objEmpresa->buscar($row2['idempresa']);
                    $objResponsable = new ResponsableV();
                    $objResponsable->buscar($row2['rnumeroempleado']);
                    $this->cargar($codviaje, $row2['vdestino'], $row2['vcantmaxpasajeros'], [], $objResponsable, $row2['vimporte'], $objEmpresa);
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
     ** Inserta un nuevo viaje a la base de datos según los datos actuales almacenados
     ** en los atributos del objeto Viaje.
     ** Retorna true si tiene éxito en la operación, false en caso contrario
     *  @return boolean
     */
    public function insertar()
    {
        $base = new BaseDatos();
        $resp = null;
        $consultaInsertar = "INSERT INTO viaje(vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado,vimporte)
				VALUES ('" . $this->getDestino() . "'," . $this->getCantMaximaPasajeros() . "," . $this->getEmpresa()->getIdEmpresa() . "," . $this->getResponsable()->getNumEmpleado() . "," . $this->getCosto() . ");";
        if ($base->Iniciar()) {
            $id = $base->devuelveIDInsercion($consultaInsertar);
            if ($id != null) {
                $resp = true;
                $this->setCodViaje($id);
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }

    /**
     ** Busca todos los viajes que cumplan una condición y devuelve un arreglo
     ** que los contiene.
     * @param string $condicion
     * @return array
     */
    public function listar($condicion)
    {
        $arregloViajes = null;
        $base = new BaseDatos();
        $consultaViajes = "Select * from viaje";
        $arregloViajes = array();
        if ($condicion != "") {
            $consultaViajes = $consultaViajes . ' where ' . $condicion;
        }
        $consultaViajes .= " order by idviaje ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaViajes)) {
                while ($row2 = $base->Registro()) {
                    $objViaje = new Viaje();
                    $objViaje->buscar($row2['idviaje']);
                    array_push($arregloViajes, $objViaje);
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $arregloViajes;
    }

    /**
     ** Modifica todos los campos del viaje actual (identificado por su código de viaje)
     ** en la base de datos según el estado actual de todos sus atributos.
     ** Retorna true si tiene éxito en la operación, false en caso contrario
     *  @return boolean
     */
    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $consultaModifica = "UPDATE viaje SET vdestino='" . $this->getDestino() . "',vcantmaxpasajeros=" . $this->getCantMaximaPasajeros() . "
                           ,idempresa=" . $this->getEmpresa()->getIdEmpresa() . ",rnumeroempleado=" . $this->getResponsable()->getNumEmpleado() . ",vimporte=" . $this->getCosto() . " WHERE idviaje =" . $this->getCodViaje();
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
     ** Elimina un viaje de la base de datos según su código de viaje.
     ** Lee el código de viaje del Viaje actual y lo envía en la consulta.
     ** Retorna true si tiene éxito en la operación, false en caso contrario
     *  @return boolean
     */
    public function eliminar()
    {
        $base = new BaseDatos();
        $resp = false;
        if ($base->Iniciar()) {
            $consultaBorra = "DELETE FROM viaje WHERE idviaje=" . $this->getCodViaje();
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
