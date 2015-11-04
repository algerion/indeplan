<?php
include_once('../compartidos/clases/DbCon.php');

class AsignaIndCG extends TPage
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
		$this->cn->enlaza($this->ddlCG, "SELECT id_cg, CONCAT(clave, ' ', denominacion) as cg FROM cg");
		$this->cn->enlaza($this->ddlFrecuencia, "SELECT id_frecuencia, frecuencia FROM frecuencia");
		$this->ddlCG_Changed();
	}
	
	public function ddlCG_Changed($sender = null, $param = null)
	{
		$this->cn->enlaza($this->ddlPrograma, "SELECT DISTINCT p.id_programa, p.programa FROM programas p JOIN cg_programas_indicadores cpi ON p.id_programa = cpi.id_programa WHERE cpi.id_cg = :id_cg", array("id_cg"=>$this->ddlCG->SelectedValue));
		$this->ddlPrograma_Changed();
	}
	
	public function ddlPrograma_Changed($sender = null, $param = null)
	{
		$this->cn->enlaza($this->lstIndicLibres, "SELECT DISTINCT id_indicador, indicador FROM indicadores WHERE id_indicador NOT IN(SELECT DISTINCT i.id_indicador FROM indicadores i LEFT JOIN cg_programas_indicadores cpi ON i.id_indicador = cpi.id_indicador WHERE cpi.id_cg = :id_cg AND cpi.id_programa = :id_programa)", array("id_cg"=>$this->ddlCG->SelectedValue, "id_programa"=>$this->ddlPrograma->SelectedValue));
		$this->cn->enlaza($this->lstIndicAsoc, "SELECT DISTINCT i.id_indicador, i.indicador FROM indicadores i LEFT JOIN cg_programas_indicadores cpi ON i.id_indicador = cpi.id_indicador WHERE cpi.id_cg = :id_cg AND cpi.id_programa = :id_programa", array("id_cg"=>$this->ddlCG->SelectedValue, "id_programa"=>$this->ddlPrograma->SelectedValue));
		$this->lstIndicAsoc_Changed();
	}
	
	public function lstIndicLibres_Changed($sender = null, $param = null)
	{
		$this->ddlFrecuencia->SelectedValue = 0;
		$this->txtMetaNum->Text = 0;
	}
	
	public function lstIndicAsoc_Changed($sender = null, $param = null)
	{
		$result = $this->cn->consulta("SELECT id_frecuencia, meta_num FROM cg_programas_indicadores WHERE id_cg = :id_cg AND id_programa = :id_programa AND id_indicador = :id_indicador", array("id_cg"=>$this->ddlCG->SelectedValue, "id_programa"=>$this->ddlPrograma->SelectedValue, "id_indicador"=>$this->lstIndicAsoc->SelectedValue));
		$this->ddlFrecuencia->SelectedValue = $result[0]["id_frecuencia"];
		$this->txtMetaNum->Text = $result[0]["meta_num"];
	}
	
	public function btnAgregar_Click($sender, $param)
	{
		$parametros = array(
				"id_cg"=>$this->ddlCG->SelectedValue,
				"id_programa"=>$this->ddlPrograma->SelectedValue,
				"id_indicador"=>$this->lstIndicLibres->SelectedValue,
				"id_frecuencia"=>$this->ddlFrecuencia->SelectedValue,
				"meta_num"=>$this->txtMetaNum->Text,
				"avance"=>0
		);
		$this->cn->inserta("cg_programas_indicadores", $parametros);
		$this->ddlPrograma_Changed();
	}

	public function btnEliminar_Click($sender, $param)
	{
		$parametros = array(
				"id_cg"=>$this->ddlCG->SelectedValue,
				"id_programa"=>$this->ddlPrograma->SelectedValue,
				"id_indicador"=>$this->lstIndicAsoc->SelectedValue,
		);
		$this->cn->elimina("cg_programas_indicadores", $parametros);
		$this->ddlPrograma_Changed();
	}
}
?>