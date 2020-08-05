<script>
function pesquisarPackages(pagina){
	$("#pagina").val(pagina);
	$("#formSearch").submit();
}

</script>
<section class="tabbased-search-area">
	<?php
	$ob = new Grupo();
	$locais = $ob->getGruposAgrupadosPorLocais();
	$anos = $ob->getGruposAgrupadosPorAno();
	?>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
			<div class="hotels-form">
							<form action="packages.php" method="post" id="formSearch" >
								<input type="hidden" name="pagina" id="pagina" value=""/>
								<div class="hotel-input-6 input-b">
									<input type="text" name="termo" id="keyword" value="<?=isset($_REQUEST['termo']) ? $_REQUEST['termo'] : ''?>" class="hotel-input-first" placeholder="Digite sua pesquisa">
								</div>
								<div class="hotel-input-4 input-b">
									<select id='standard1' name='local' class='custom-select'>
										<option value=''>Local</option>
										<?php foreach ($locais as $key => $value) {
										if(isset($_REQUEST['local'])){
										?>
										<option value='<?=$value->local?>' <?=$_REQUEST['local'] == $value->local ? 'selected' : ''  ?>><?=$value->local?></option>
										<? }else{?>
										<option value='<?=$value->local?>'><?=$value->local?></option>
										<? 
										}
										}?>
									</select>
								</div> 
								<div class="hotel-input-4 input-b">
									<select id='standard2' name='ano' class='custom-select'>
										<option value=''>Ano</option>
										<?php foreach ($anos as $key => $value) {
										if(isset($_REQUEST['ano'])){
											?>
											<option value='<?=$value->ano?>' <?=$_REQUEST['ano'] == $value->ano ? 'selected' : ''  ?>><?=$value->ano?></option>
											<? }else{?>
											<option value='<?=$value->ano?>'><?=$value->ano?></option>
											<? 
											}
											}?>
									</select>
								</div> 
								
								<div class="searc-btn-7">
									<button type="submit">Pesquisar</button>
								</div>
							</form>
						</div>
			</div>
		</div>
	</div>
</section>