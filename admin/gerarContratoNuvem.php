<?php
//contratos em nuvem
		if($this->pacoteOpcional == 1){
			$taxaAdesao = $this->money($oGrupo->valorAdesao + $oGrupo->valorAdesaoOpcional,"atb");
		}else{
			$taxaAdesao = $this->money($oGrupo->valorAdesao,"atb");
		}
		switch ($oGrupo->modeloContrato) {
			case 'contrato1.php':
				# code...
				$layout = "1";
				break;
				case 'contrato2.php':
				# code...
				$layout = "2";
				break;
				case 'contrato3.php':
				# code...
				$layout = "3";
				break;
				case 'contrato4.php':
				# code...
				$layout = "14";
				break;
			default:
				# code...
				$layout = "1";
				break;
		}

		$data = array("identidicadorLayout"=>$layout,
			"numeroControleEmpresa"=>$this->id,
			"documentoCliente"=>$oCliente->cpf,
			"nomeCliente"=>$oCliente->nomeCompleto,
			"variaveis"=> array(
				array("nome"=>"nomeCompleto","valor"=>$oCliente->nomeCompleto),
				array("nome"=>"estado_civil","valor"=>$oCliente->estadoCivil->descricao),
				array("nome"=>"rg","valor"=>$oCliente->rg),
				array("nome"=>"rgOrgaoExpedidor","valor"=>$oCliente->orgaoEmissorRg),
				array("nome"=>"cpf","valor"=>$oCliente->cpf),
				array("nome"=>"endereco","valor"=>$oCliente->endereco),
				array("nome"=>"cidade","valor"=>$oCliente->cidadeEndereco),
				array("nome"=>"uf","valor"=>$oCliente->estadoNascimento),
				array("nome"=>"nacionalidade","valor"=>$oCliente->nacionalidade),				
				array("nome"=>"taxaAdesao","valor"=>$taxaAdesao),
				array("nome"=>"CIFRAO","valor"=>$oGrupo->moeda->cifrao),
				array("nome"=>"total","valor"=>$this->money($this->valorTotal,"atb")),
				array("nome"=>"totalPassagem","valor"=>$_REQUEST['valorPassagem']),
				array("nome"=>"dia","valor"=>date("d")),
				array("nome"=>"mes","valor"=>$this->mesExtenso(date("m"))),
				array("nome"=>"ano","valor"=>date("Y"))));				
			

			$this->salvaContratoEmNuvem($data);