<?php
Prado::using('System.Data.ActiveRecord.*'); 

class Centro extends TActiveRecord 
{
    const TABLE = 'cg';
 
    public $id_cg; 
    public $clave;
	public $denominacion;
}
?>