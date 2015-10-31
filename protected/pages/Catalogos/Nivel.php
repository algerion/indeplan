<?php
Prado::using('System.Data.ActiveRecord.*'); 

class Nivel extends TActiveRecord 
{
    const TABLE = 'niveles';
 
    public $id_nivel;
    public $nivel;

	public static $RELATION=array
	(
		'programas' => array(self::HAS_MANY, 'Programa', 'id_nivel'),
	);
	 
	public static function finder($className=__CLASS__)
	{
			return parent::finder($className);
	}
}
?>