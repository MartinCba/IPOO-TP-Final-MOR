<?php
class ResponsableV
{
    private $numEmpleado;
    private $numLicencia;
    private $nombre;
    private $apellido;
    private $mensajeOperacion;

    /**
     ** Crea una instancia de la clase ResponsableV
     */
    public function __construct()
    {
        $this->numEmpleado = '';
        $this->numLicencia = '';
        $this->nombre = '';
        $this->apellido = '';
    }

    /**
     ** Función que asigna los valores ingresados por párametro
     ** a los atributos del ResponsableV
     *  @param int $numEmp
     *  @param int $numLic
     *  @param string $nom
     *  @param string $ape
     */
    public function cargar($numEmp, $numLic, $nom, $ape)
    {
        $this->numEmpleado = $numEmp;
        $this->numLicencia = $numLic;
        $this->nombre = $nom;
        $this->apellido = $ape;
    }

    //?                 ╔════════════════════════════════════════════════════════════════════════════╗
    //?                 ║                             METODOS DE ACCESO                              ║
    //?                 ╚════════════════════════════════════════════════════════════════════════════╝

    public function setNumEmpleado($numEmpleadoNuevo)
    {
        $this->numEmpleado = $numEmpleadoNuevo;
    }
    public function getNumEmpleado()
    {
        return $this->numEmpleado;
    }
    public function setNumLicencia($numLicenciaNuevo)
    {
        $this->numLicencia = $numLicenciaNuevo;
    }
    public function getNumLicencia()
    {
        return $this->numLicencia;
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
     ** Devuelve un string que contiene toda la información del estado de una instancia de tipo ResponsableV
     * @return string
     */
    public function __toString()
    {
        return 'Número de empleado: ' . $this->getNumEmpleado() . "\n" .
        'Número de licencia: ' . $this->getNumLicencia() . "\n" .
        'Nombre: ' . $this->getNombre() . "\n" .
        'Apellido: ' . $this->getApellido() . "\n";
    }

    //?                 ╔════════════════════════════════════════════════════════════════════════════╗
    //?                 ║                   METODOS RELACIONADOS A LA BASE DE DATOS                  ║
    //?                 ╚════════════════════════════════════════════════════════════════════════════╝

    /**
     ** Inserta un nuevo responsable a la base de datos según los datos actuales almacenados
     ** en los atributos del objeto Responsable.
     ** Retorna true si tiene éxito en la operación, false en caso contrario
     * @return boolean
     */
    public function insertar()
    {
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO responsable(rnumerolicencia, rnombre,rapellido)
				VALUES (" . $this->getNumLicencia() . ",'" . $this->getNombre() . "','" . $this->getApellido() . "')";
        if ($base->Iniciar()) {
            $id = $base->devuelveIDInsercion($consultaInsertar);
            if ($id != null) {
                $resp = true;
                $this->setNumEmpleado($id);
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }

    /**
     ** Recupera los datos de un responsable en la base de datos a partir de un número de empleado ingresado
     ** y los setea al objeto responsable actual
     ** Retorna true si tiene éxito en la operación, false en caso contrario
     *  @param int $numEmpleado
     *  @return boolean
     */
    public function buscar($numEmpleado)
    {
        $base = new BaseDatos();
        $consultaResp = "Select * from responsable where rnumeroempleado=" . $numEmpleado;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaResp)) {
                if ($row2 = $base->Registro()) {
                    $this->cargar($numEmpleado, $row2['rnumerolicencia'], $row2['rnombre'], $row2['rapellido']);
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
     ** Modifica todos los campos del responsable actual (identificado por su número de empleado)
     ** en la base de datos según el estado actual de todos sus atributos.
     ** Previamente se tuvo que hacer un set a cada atributo a modificar.
     ** Retorna true si tiene éxito en la operación, false en caso contrario
     *  @return boolean
     */
    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $consultaModifica = "UPDATE responsable SET rnumerolicencia=" . $this->getNumLicencia() . ",rnombre='" . $this->getNombre() . "'
                           ,rapellido='" . $this->getApellido() . "'" . " WHERE rnumeroempleado =" . $this->getNumEmpleado();
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
     ** Elimina un responsable de la base de datos según su número de empleado.
     ** Lee el número de empleado del responsable actual y lo envía en la consulta.
     ** Retorna true si tiene éxito en la operación, false en caso contrario
     * @return boolean
     */
    public function eliminar()
    {
        $base = new BaseDatos();
        $resp = false;
        if ($base->Iniciar()) {
            $consultaBorra = "DELETE FROM responsable WHERE rnumeroempleado=" . $this->getNumEmpleado();
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

    /**
     ** Busca todos los responsables que cumplan una condición y devuelve un arreglo
     ** que los contiene.
     * @param string $condicion
     * @return array
     */
    public function listar($condicion = "")
    {
        $arregloResp = null;
        $base = new BaseDatos();
        $consultaResp = "Select * from responsable";
        if ($condicion != "") {
            $consultaResp = $consultaResp . ' where ' . $condicion;
        }
        $consultaResp .= " order by rnumeroempleado ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaResp)) {
                $arregloResp = array();
                while ($row2 = $base->Registro()) {
                    $objResp = new ResponsableV();
                    $objResp->buscar($row2['rnumeroempleado']);
                    array_push($arregloResp, $objResp);
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $arregloResp;
    }
}
