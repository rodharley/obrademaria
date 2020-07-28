<?php
class BandeiraCartao extends Persistencia{
	var $id = NULL;
	var $descricao;
	var $imagem;
	
	
		public function recuperaTotal(){
		$rs = $this->DAO_ExecutarQuery("select count(id) as total from ag_bandeira");	
		return $this->DAO_Result($rs,"total",0);
	}
	
	
	
	public function incluir(){
		$this->descricao = $_POST['descricao'];
		//uploadArquivo roteiro
		if($_FILES['logo']['name'] != ''){
			$nomeImagem = date("d_m_Y_H_i_s").$this->removerAcento($_FILES['logo']['name']);
			$diretorio = $this->URI."/img/bandeiras/";		
			$this->uploadArquivo($_FILES['logo'],$nomeImagem,$diretorio);
			
			$this->imagem = $nomeImagem;
		}		
		
		$newid = $this->save();
		$_SESSION['tupi.mensagem'] = 62;		
	}
	
	public function alterar(){
		$this->getById($_POST['id']);
		$this->descricao = $_POST['descricao'];
		if($_FILES['logo']['name'] != ''){
			$nomeImagem = date("d_m_Y_H_i_s").$this->removerAcento($_FILES['logo']['name']);
			$diretorio = $this->URI."/img/bandeiras/";		
			if($this->logomarca != "")
				unlink($diretorio.$this->logomarca);
			$this->uploadArquivo($_FILES['logo'],$nomeImagem,$diretorio);			
			$this->imagem = $nomeImagem;
		}
		$newid = $this->save();
		$_SESSION['tupi.mensagem'] = 63;		
		return $newid;
	}
	
	function excluir(){
		$idGrupoExc = $this->md5_Decrypt($_REQUEST['idBA']);
		$this->getById($idGrupoExc);
		$this->delete($idGrupoExc);
		$_SESSION['tupi.mensagem'] = 64;			
		
	}	
}
?>