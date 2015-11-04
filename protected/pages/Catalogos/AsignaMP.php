<?php
include_once('../compartidos/clases/DbCon.php');

class AsignaMP extends TPage
{
	var $cn;

	public function onLoad($param)
	{
		parent::onLoad($param);

		$this->cn = new DbCon($this, "db");

		if(!$this->IsPostBack)
		{
			$this->ddls();
		}
	}
	
	public function ddls()
	{
		$this->cn->enlaza($this->ddlPrograma, "SELECT p.id_programa, p.programa FROM programas p");
		$this->ddlPrograma_Changed();
	}
	
	public function ddlPrograma_Changed($sender = null, $param = null)
	{
		$this->cn->enlaza($this->dgMediosAsoc, "SELECT m.id_medio, m.causa, m.medio FROM medios m JOIN programas_medios pm ON m.id_medio = pm.id_medio WHERE pm.id_programa = :id_programa", array("id_programa"=>$this->ddlPrograma->SelectedValue));
		$this->cn->enlaza($this->dgMediosLibres, "SELECT DISTINCT id_medio, causa, medio FROM medios WHERE id_medio NOT IN (SELECT m.id_medio FROM medios m JOIN programas_medios pm ON m.id_medio = pm.id_medio WHERE pm.id_programa = :id_programa)", array("id_programa"=>$this->ddlPrograma->SelectedValue));
	}
	
	public function dgMediosLibres_Update($sender, $param)
	{
		$parametros = array(
			"id_programa"=>$this->ddlPrograma->SelectedValue,
			"id_medio"=>$this->dgMediosLibres->DataKeys[$param->Item->ItemIndex]
		);
		$this->cn->inserta("programas_medios", $parametros);
		$this->ddlPrograma_Changed();
	}

	public function dgMediosAsoc_Delete($sender, $param)
	{
		$parametros = array(
			"id_programa"=>$this->ddlPrograma->SelectedValue,
			"id_medio"=>$this->dgMediosAsoc->DataKeys[$param->Item->ItemIndex]
		);
		$this->cn->elimina("programas_medios", $parametros);
		$this->ddlPrograma_Changed();
	}
}
?>