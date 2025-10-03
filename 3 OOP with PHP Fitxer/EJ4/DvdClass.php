<?php
include("soporteMainClass.php");


class Dvd extends Soporte
{
    public $idiomas;
    private $formatoPantalla;


    public function __set($propiedad, $valor)
    {
        $this->$propiedad = $valor;
    }

    public function __get($propiedad)
    {
        return $this->$propiedad;
    }
    

    public function __construct($titulo, $numero, $precio, $idiomas, $formatoPantalla)
    {
        parent::__construct($titulo, $numero, $precio);
        $this->idiomas = explode(",", $idiomas);

        $this->formatoPantalla = $formatoPantalla;
    }

    public function muestraResumen()
    {
        return parent::muestraResumen() . ", Idiomas: " . implode(", ", $this->idiomas) . ", Formato de pantalla: " . $this->formatoPantalla;
    }


}
?>