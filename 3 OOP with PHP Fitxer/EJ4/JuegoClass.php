<?php
include("soporteMainClass.php");


class Juego extends Soporte
{
    public $consola;
    private $numJugadores;

    public function __construct($titulo, $numero, $precio, $consola, $numJugadores)
    {
        parent::__construct($titulo, $numero, $precio);
        $this->consola = $consola;
        $this->numJugadores = $numJugadores;
    }

    public function muestraResumen()
    {
        return parent::muestraResumen() . ", Consola: " . $this->consola . ", Número de jugadores: " . $this->numJugadores;
    }
}
?>