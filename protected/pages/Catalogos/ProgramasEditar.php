<?php
include_once('../compartidos/clases/DbCon.php');

class ProgramasEditar extends TPage
{
	var $cn;

	public function onLoad($param)
	{
		parent::onLoad($param);

		$this->cn = new DbCon($this, "db");

		if(!$this->IsPostBack)
		{
			$consulta = "SELECT * FROM niveles";
			$this->cn->enlaza($this->ddlNivel, $consulta);
			if(isset($this->Request["id"]))
			{
				$consulta = "SELECT * FROM programas WHERE id_programa = :id_programa";
				$resultado = $this->cn->consulta($consulta, array("id_programa"=>$this->Request["id"]));
				$this->txtProblema->Text = $resultado[0]["problema"];
				$this->txtEfectos->Text = $resultado[0]["efectos"];
				$this->txtDeseado->Text = $resultado[0]["deseado"];
				$this->txtFines->Text = $resultado[0]["fines"];
				$this->txtPrograma->Text = $resultado[0]["programa"];
				$this->ddlNivel->SelectedValue = $resultado[0]["id_nivel"];
				$this->txtObjetivo->Text = $resultado[0]["objetivo"];
			}
		}
	}
	
	public function btnGuardar_Click($sender, $param)
	{
		$parametros = array(
				"problema"=>$this->txtProblema->Text,
				"efectos"=>$this->txtEfectos->Text,
				"deseado"=>$this->txtDeseado->Text,
				"fines"=>$this->txtFines->Text,
				"programa"=>$this->txtPrograma->Text,
				"id_nivel"=>$this->ddlNivel->SelectedValue,
				"objetivo"=>$this->txtObjetivo->Text
		);
		if(isset($this->Request["id"]))
		{
			$busqueda = array("id_programa"=>$this->Request["id"]);
			$this->cn->actualiza("programas", $parametros, $busqueda);
		}
		else
			$this->cn->inserta("programas", $parametros);
		$this->getClientScript()->registerBeginScript("guardado",
				"alert('Proceso terminado');\n"
				. "document.location.href = 'index.php?page=Catalogos.ProgramasDG';\n"
		);
	}
}
?>