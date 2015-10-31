<?php
Prado::using('System.Data.ActiveRecord.*'); 

class Indicador extends TActiveRecord 
{
    const TABLE = 'indicadores';
 
    public $id_indicador;
    public $indicador;
	public $unidad;
	public $formula;
	public $meta;
}
?>