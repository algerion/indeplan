<?php
Prado::using('System.Data.ActiveRecord.*'); 

class Arbol extends TActiveRecord 
{
    const TABLE = 'medios';
 
    public $id_medio;
    public $causa;
	public $medio;
}
?>