<?php
include_once('../compartidos/clases/DbCon.php');

class ProgramasDG extends TPage
{
	var $cn;

	public function onLoad($param)
	{
		parent::onLoad($param);

		$this->cn = new DbCon($this, "db");

		if(!$this->IsPostBack)
		{
			$consulta = "SELECT DISTINCT id_programa, problema, " .
//					"GROUP_CONCAT('-', causa SEPARATOR '<br />') AS causas, " .
					"efectos, deseado, " .
//					"GROUP_CONCAT('-', medio SEPARATOR '<br />') AS medios, " .
					"fines, programa, nivel, objetivo " .
					"FROM programas p JOIN niveles n ON p.id_nivel = n.id_nivel " .
//					"JOIN programas_medios pm ON p.id_programa = pm.id_programa " .
//					"JOIN medios m ON pm.id_medio = m.id_medio " .
//					"GROUP BY problema, efectos, deseado, fines, programa, nivel, objetivo " .
					"ORDER BY p.id_programa";
			$this->dgProgramas->DataSource = $this->cn->Consulta($consulta);
			$this->dgProgramas->dataBind();
		}
	}
	
	public function btnNuevo_Click($sender, $param)
	{
		$this->Response->redirect("index.php?page=Catalogos.ProgramasEditar");
	}
}
?>