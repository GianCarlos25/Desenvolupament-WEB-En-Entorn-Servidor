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

class CintaVideo extends Soporte
{

    private $duracion;

    public function __construct($titulo, $numero, $precio, $duracion)
    {
        parent::__construct($titulo, $numero, $precio);
        $this->duracion = $duracion;
    }

    public function __set($propiedad, $valor)
    {
        $this->$propiedad = $valor;
    }

    public function __get($propiedad)
    {
        return $this->$propiedad;
    }

    public function muestraResumen()
    {
        return parent::muestraResumen() . ", Duración: " . $this->duracion . " minutos.";
    }


}

class Dvd extends Soporte
{

    public $idiomas;
    private $formatoPantalla;

    public function __construct($titulo, $numero, $precio, $idiomas, $formatoPantalla)
    {
        parent::__construct($titulo, $numero, $precio);
        $this->idiomas = $idiomas;
        $this->formatoPantalla = $formatoPantalla;
    }

    public function __set($propiedad, $valor)
    {
        $this->$propiedad = $valor;
    }

    public function __get($propiedad)
    {
        return $this->$propiedad;
    }

    public function muestraResumen()
    {
        return parent::muestraResumen() . ", Idiomas: " . implode(", ", $this->idiomas) . ", Formato de pantalla: " . $this->formatoPantalla;
    }

}

class Juego extends Soporte
{
    public $consola;
    private $minNumJugaodores;
    private $maxNumJugadores;

    public function __construct($titulo, $numero, $precio, $consola, $minNumJugadores, $maxNumJugadores)
    {
        parent::__construct($titulo, $numero, $precio);
        $this->consola = $consola;
        $this->minNumJugadores = $minNumJugadores;
        $this->maxNumJugadores = $maxNumJugadores;
    }
    public function __set($propiedad, $valor)
    {
        $this->$propiedad = $valor;
    }

    public function __get($propiedad)
    {
        return $this->$propiedad;
    }

    public function muestraResumen()
    {
        return parent::muestraResumen() . ", Consola: " . $this->consola . ", Número de jugadores: " . $this->minNumJugadores . " - " . $this->maxNumJugadores;
    }

    public function muestraJugadoresPosibles()
    {
        return $this->minNumJugadores . " - " . $this->maxNumJugadores;
    }
}

?>