<?php
Prado::using('System.Data.ActiveRecord.*'); 

class Frecuencia extends TActiveRecord 
{
    const TABLE = 'frecuencia';
 
    public $id_frecuencia;
    public $frecuencia;
}
?>