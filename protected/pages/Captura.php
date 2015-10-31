<?php
include_once('../compartidos/clases/conexion.php');
include_once('../compartidos/clases/listas.php');

class Captura extends TPage
{
	var $dbConexion;

	public function onLoad($param)
	{
		parent::onLoad($param);

		$this->dbConexion = Conexion::getConexion($this->Application, "db");
		Conexion::createConfiguracion();

		if(!$this->IsPostBack)
		{
			$this->ddls();
		}
	}
	
	public function ddls()
	{
		$this->enlazaCG();
		//$this->enlazaProgramas();
		//$this->enlazaMedios();
	}

	public function enlazaCG($sender = null, $param = null)
	{
		Listas::EnlazaLista($this->dbConexion, "SELECT DISTINCT cg.id_cg, CONCAT(cg.clave, '. ', cg.denominacion) AS cg FROM cg JOIN cg_programas_indicadores cpi on cg.id_cg = cpi.id_cg ORDER BY clave", $this->ddlCG);
		$this->ddlCG->raiseEvent("OnSelectedIndexChanged", $this->ddlCG, new TCallbackEventParameter(null, null));
	}

	public function enlazaProgramas($sender = null, $param = null)
	{
		Listas::EnlazaLista($this->dbConexion, "SELECT DISTINCT p.id_programa, p.programa FROM programas p JOIN cg_programas_indicadores cpi on p.id_programa = cpi.id_programa WHERE cpi.id_cg = " . $this->ddlCG->SelectedValue, $this->ddlPrograma);
		$this->ddlPrograma->raiseEvent("OnSelectedIndexChanged", $this->ddlPrograma, null);
	}

	public function enlazaddlUnidades($sender = null, $param = null)
	{
		Listas::EnlazaLista($this->dbConexion, "SELECT i.id_indicador, indicador FROM indicadores i JOIN cg_programas_indicadores cpi ON i.id_indicador = cpi.id_indicador WHERE cpi.id_programa = " . $this->ddlPrograma->SelectedValue . " AND cpi.id_cg = " . $this->ddlCG->SelectedValue, $this->ddlIndicador);
		$this->ddlIndicador->raiseEvent("OnSelectedIndexChanged", $this->ddlIndicador, null);
	}
	
	public function escribeUnidad($sender = null, $param = null)
	{
		$indicador = Conexion::Retorna_Consulta($this->dbConexion, "indicadores", array("unidad", "meta"), array("id_indicador"=>$this->ddlIndicador->SelectedValue));
		$nums = Conexion::Retorna_Consulta($this->dbConexion, "cg_programas_indicadores", array("meta_num", "avance"), array("id_cg"=>$this->ddlCG->SelectedValue, "id_programa"=>$this->ddlPrograma->SelectedValue, "id_indicador"=>$this->ddlIndicador->SelectedValue));
		$this->lblUnidad->Text = $indicador[0]["unidad"];
		$meta_num = $nums[0]["meta_num"];
		$this->txtAvance->Text = $nums[0]["avance"];
		$this->txtMeta->Text = str_replace("##", $meta_num, $indicador[0]["meta"]);
	}

	public function btnGuardar_Click($sender, $param)
	{
		$parametros = array("avance"=>$this->txtAvance->Text);
		$busqueda = array(
				"id_cg"=>$this->ddlCG->SelectedValue,
				"id_programa"=>$this->ddlPrograma->SelectedValue,
				"id_indicador"=>$this->ddlIndicador->SelectedValue
				);
		Conexion::Actualiza_Registro($this->dbConexion, "cg_programas_indicadores", $parametros, $busqueda);
		$this->getClientScript()->registerBeginScript("guardado",
				"alert('Indicador actualizado');\n"
//				. "document.location.href = 'index.php?page=Captura';\n"
			);
	}
}
?>