<?php
class Empresa
{
    private $idEmpresa;
    private $nombre;
    private $direccion;
    private $mensajeOperacion;

    /**
     ** Crea una instancia de la clase Empresa
     */
    public function __construct()
    {
        $this->idEmpresa = 0;
        $this->nombre = '';
        $this->direccion = '';
    }

    /**
     ** Función que asigna los valores ingresados por párametro
     ** a los atributos de la Empresa
     * @param int $idEmpresa
     * @param string $nombre
     * @param string $direccion
     */
    public function cargar($idEmpresa, $nombre, $direccion)
    {
        $this->idEmpresa = $idEmpresa;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
    }

    //?                 ╔════════════════════════════════════════════════════════════════════════════╗
    //?                 ║                             METODOS DE ACCESO                              ║
    //?                 ╚════════════════════════════════════════════════════════════════════════════╝

    public function setIdEmpresa($nuevoId)
    {
        $this->idEmpresa = $nuevoId;
    }
    public function getIdEmpresa()
    {
        return $this->idEmpresa;
    }
    public function setNombre($nuevoNombre)
    {
        $this->nombre = $nuevoNombre;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function setDireccion($nuevaDireccion)
    {
        $this->direccion = $nuevaDireccion;
    }
    public function getDireccion()
    {
        return $this->direccion;
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
     ** Devuelve un string que contiene toda la información del estado de una instancia de tipo Empresa
     *  @return string
     */
    public function __toString()
    {
        $mensaje = "╔════════════════════════════════════════════════════════════╗\n";
        $mensaje .= "║                           EMPRESA                          ║\n";
        $mensaje .= "╚════════════════════════════════════════════════════════════╝\n";
        $mensaje .= "  ID: " . $this->getIdEmpresa() . " ║ Nombre: " . $this->getNombre() . " ║ Direccion: " . $this->getDireccion() . " \n\n";

        return $mensaje;
    }

    //?                 ╔════════════════════════════════════════════════════════════════════════════╗
    //?                 ║                   METODOS RELACIONADOS A LA BASE DE DATOS                  ║
    //?                 ╚════════════════════════════════════════════════════════════════════════════╝

    /**
     ** Recupera los datos de una empresa en la base de datos a partir de un ID de empresa ingresado
     ** y los setea al objeto empresa actual.
     ** Retorna true si tiene éxito en la operación, false en caso contrario
     *  @param int $idEmpresa
     *  @return boolean
     */
    public function buscar($idEmpresa)
    {
        $base = new BaseDatos();
        $consultaEmpresa = "Select * from empresa where idempresa=" . $idEmpresa;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaEmpresa)) {
                if ($row2 = $base->Registro()) {
                    $this->cargar($idEmpresa, $row2['enombre'], $row2['edireccion']);
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
     ** Busca todos las empresas que cumplan una condición y devuelve un arreglo
     ** que las contiene.
     *  @param string $condicion
     *  @return array|null
     */
    public function listar($condicion)
    {
        $arregloEmp = null;
        $base = new BaseDatos();
        $consultaEmpresa = "Select * from empresa ";
        if ($condicion != "") {
            $consultaEmpresa = $consultaEmpresa . ' where ' . $condicion;
        }
        $consultaEmpresa .= " order by idempresa ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaEmpresa)) {
                $arregloEmp = array();
                while ($row2 = $base->Registro()) {
                    $idEmpresa = $row2['idempresa'];
                    $nombre = $row2['enombre'];
                    $direccion = $row2['edireccion'];
                    $objViaje = new Viaje();
                    $colViajes = $objViaje->listar("idempresa =" . $idEmpresa);
                    $emp = new Empresa();
                    $emp->cargar($idEmpresa, $nombre, $direccion, []);
                    array_push($arregloEmp, $emp);
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $arregloEmp;
    }

    /**
     ** Inserta una nueva empresa a la base de datos según los datos actuales almacenados
     ** en los atributos del objeto Empresa.
     ** Retorna true si tiene éxito en la operación, false en caso contrario
     *  @return boolean
     */
    public function insertar()
    {
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO empresa(enombre, edireccion)
				VALUES ('" . $this->getNombre() . "','" . $this->getDireccion() . "')";
        if ($base->Iniciar()) {
            $id = $base->devuelveIDInsercion($consultaInsertar);
            if ($id != null) {
                $resp = true;
                $this->setIdEmpresa($id);
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }

    /**
     ** Modifica todos los campos de la empresa actual (identificado por su ID de empresa)
     ** en la base de datos según el estado actual de todos sus atributos.
     ** Retorna true si tiene éxito en la operación, false en caso contrario
     *  @return boolean
     */
    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $consultaModifica = "UPDATE empresa SET enombre='" . $this->getNombre() . "',edireccion='" . $this->getDireccion() . "'
                           " . " WHERE idempresa =" . $this->getIdEmpresa();
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
     ** Elimina una empresa de la base de datos según su ID de empresa.
     ** Lee el ID de empresa de la empresa actual y lo envía en la consulta.
     ** Retorna true si tiene éxito en la operación, false en caso contrario
     *  @return boolean
     */
    public function eliminar()
    {
        $base = new BaseDatos();
        $resp = false;
        if ($base->Iniciar()) {
            $consultaBorra = "DELETE FROM empresa WHERE idempresa=" . $this->getIdEmpresa();
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
