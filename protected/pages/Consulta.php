<?php
include_once('../compartidos/clases/DbCon.php');

class Consulta extends TPage
{
	var $cn;

	public function onLoad($param)
	{
		parent::onLoad($param);

		$this->cn = new DbCon($this, "db");

		if(!$this->IsPostBack)
		{
			$consulta = "SELECT DISTINCT cg.clave, cg.denominacion, p.problema, " .
					"GROUP_CONCAT(DISTINCT '-', m.causa SEPARATOR '<br />') AS causas, p.efectos, " .
					"p.deseado, GROUP_CONCAT(DISTINCT '-', m.medio SEPARATOR '<br />') AS medios, " .
					"p.fines, p.programa, n.nivel, p.objetivo, " .
					"REPLACE(i.meta, '##', cpi.meta_num) as meta, i.unidad, i.indicador, " .
					"i.formula, cpi.avance, cpi.avance / cpi.meta_num AS porcentaje, f.frecuencia " .
					"FROM cg_programas_indicadores cpi JOIN cg ON cpi.id_cg = cg.id_cg " .
					"JOIN programas p ON cpi.id_programa = p.id_programa " .
					"JOIN niveles n ON p.id_nivel = n.id_nivel " .
					"JOIN programas_medios pm ON p.id_programa = pm.id_programa " .
					"JOIN medios m ON pm.id_medio = m.id_medio " .
					"JOIN indicadores i ON cpi.id_indicador = i.id_indicador " .
					"JOIN frecuencia f ON cpi.id_frecuencia = f.id_frecuencia " .
					"GROUP BY cg.clave, cg.denominacion, p.problema, p.efectos, p.deseado, " .
					"p.fines, p.programa, n.nivel, p.objetivo, i.meta, i.unidad, i.indicador, " .
					"i.formula, f.frecuencia  " .
					"ORDER BY p.id_programa";

			$this->dgProgramas->DataSource = $this->cn->Consulta($consulta);
			$this->dgProgramas->dataBind();
		}
	}
	
}
?>