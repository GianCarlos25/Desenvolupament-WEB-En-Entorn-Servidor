<?php

include("soporteMainClass.php");

class CintaVideo extends Soporte
{
    public $duracion;
    private $color;

    public function __set($propiedad, $valor)
    {
        $this->$propiedad = $valor;
    }

    public function __get($propiedad)
    {
        return $this->$propiedad;
    }

    public function __construct($titulo, $numero, $precio, $duracion, $color)
    {
        parent::__construct($titulo, $numero, $precio);
        $this->duracion = $duracion;
        $this->color = $color;
    }

    public function muestraResumen()
    {
        return parent::muestraResumen() . ", Duración: " . $this->duracion . " minutos, Color: " . ($this->color ? "Sí" : "No");
    }
}