<?php
Prado::using('System.Data.ActiveRecord.*'); 

class Programa extends TActiveRecord 
{
    const TABLE = 'programas';
 
    public $id_programa;
    public $programa;
	public $problema;
	public $efectos;
	public $deseado;
	public $fines;
	public $id_nivel;
	public $objetivo;

	public static $RELATION=array
	(
		'nivel' => array(self::BELONGS_TO, 'Niveles', 'id_nivel'),
	);
	 
	public static function finder($className=__CLASS__)
	{
			return parent::finder($className);
	}
}
?>