<?php

class Soporte
{
    public $titulo;
    protected $numero;
    private $precio;

    public function __construct($titulo, $numero, $precio)
    {
        $this->titulo = $titulo;
        $this->numero = $numero;
        $this->precio = $precio;
    }

    public function __set($propiedad, $valor)
    {
        $this->$propiedad = $valor;
    }

    public function __get($propiedad)
    {
        return $this->$propiedad;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function getPrecioConIVA()
    {
        return $this->precio * 1.21;
    }

    public function getNumero()
    {
        return $this->numero;
    }

    public function muestraResumen()
    {
        return "Título: " . $this->titulo . ", Número: " . $this->numero . ", Precio sin IVA: " . $this->getPrecio() . ", Precio con IVA: " . $this->getPrecioConIVA();
    }


}
?>
