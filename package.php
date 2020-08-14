<?php
include("admin/tupi.inicializar.php"); 
$menusite = 1;
if(isset($_GET['id'])){
	$obRoteiro = new Roteiro();
	$obPart = new Participante();
	$obRoteiro->getById($_GET['id']);
	$stars = $obRoteiro->getNumberStars();
	$inscritos = $obPart->recuperaTotal($obRoteiro->grupo->id);
	$vagas = $obRoteiro->grupo->maxPessoa - $inscritos;
	
}else{
	header(("location:index.php"));
}

?>
<?php include("include-header.php");?>
<body>
<!-- Preloader -->
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>	  
<?php include('include-menu.php'); ?>
<!-- blog breadcrumb version one strat here -->
<section class="breadcrumb-blog-version-one">
	<div class="single-bredcurms" style="background-image:url('img/packages/<?=$obRoteiro->image?>');">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<div class="bredcrums-content">
						<h2><?= $obRoteiro->title?></h2>
						<ul>
							<li><a href="index.php">Home</a>
							</li>
							<li class="active"><a href="single-package.html">Detalhes do Roteiro</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</section><!-- blog breadcrumb version one end here -->

<section class="section-paddings single-package-area">
	<div class="container">
		
		<div class="row">
			<!-- single package tab with details -->
			<div class="col-md-8 col-sm-12">
				<div class="single-package-details">
					<div class="single-package-title">
						<h2><?= $obRoteiro->title?></h2>
					</div>
					<ul class="package-content">
						<li>Embarque: <?= $obRoteiro->convdata($obRoteiro->grupo->dataEmbarque,"mtn")?></li>
						<li>
							<span>
							   
								<i id="star1" class="fa <?=$stars >=1 ? 'fa-star' : 'fa-star-o';?>"></i>
								<i id="star2" class="fa <?=$stars >=2 ? 'fa-star' : 'fa-star-o';?>"></i>
								<i id="star3" class="fa <?=$stars >=3 ? 'fa-star' : 'fa-star-o';?>"></i>
								<i id="star4" class="fa <?=$stars >=4 ? 'fa-star' : 'fa-star-o';?>"></i>
								<i id="star5"class="fa  <?=$stars >=5 ? 'fa-star' : 'fa-star-o';?>"></i>
							
							 </span> (<?= count($obRoteiro->reviews)?> Reviews)
						</li>						
						<li>À Vista <?= $obRoteiro->grupo->moeda->cifrao.' '.$obRoteiro->money($obRoteiro->valorComDesconto(),"atb")?></li>
					</ul>
					<div class="blog-meta">
							<ul class="post-social">
								<li><a href="javascript:void(0);" id="do_unlike"><i class="fa fa-thumbs-o-down"></i><span class="lbl_unlike"><?= $obRoteiro->unlikes?></span></a>
								</li>
								<li><a href="javascript:void(0);" id="do_like"><i class="fa fa-thumbs-o-up"></i><span class="lbl_like"><?= $obRoteiro->likes?></span></a>
								</li>
							</ul>
						</div>
					
					<div class="row">
					<?php if(count($obRoteiro->photos) > 0){ ?>
						<div class="col-md-6  col-sm-12">
						<div class="package-features-image">
						<img src="img/fotos/<?=$obRoteiro->photos[0]->name?>" alt="" class="img-responsove border-raduis-3">
					</div>
						</div>
						<?php }?>
						<div class="col-md-6  col-sm-12 tab-content">
						<div class="additional-info">
										<div class="info-title">
											<h4>Informaçoes Adicionais</h4>
											<div class="row">
												<div class="col-md-7 col-sm-7">
													<div class="info-list">
														<p><span><i class="fa fa-money"></i></span>Adesão</p>
														<p><span><i class="fa fa-money"></i></span>Taxa de Embarque</p>
														<p><span><i class="fa fa-map-marker"></i></span>Local</p>
														<p><span><i class="fa fa-clock-o"></i></span>Duração</p>
														<p><span><i class="fa fa-user"></i></span>Idade Mín.</p>
														<p><span><i class="fa fa-users"></i></span>Máx. de Pessoas</p>
														<p><span><i class="fa fa-plane"></i></span>Partida</p>
														<p><span><i class="fa fa-calendar-check-o"></i></span>Chegada</p>
														
													</div>
												</div>
												<div class="col-md-5 col-sm-5">
													<div class="info-details">														
														<p><?= $obRoteiro->grupo->moeda->cifrao." ".$obRoteiro->money($obRoteiro->grupo->valorAdesao,"atb")?></p>
														<p><?= $obRoteiro->grupo->moeda->cifrao." ".$obRoteiro->money($obRoteiro->grupo->valorTaxaEmbarque,"atb")?></p>
														<p><?= $obRoteiro->grupo->local?></p>
														<p><?= $obRoteiro->grupo->duracao?> dias</p>
														<p><?= $obRoteiro->grupo->idadeMinima?>+</p>
														<p><?= $obRoteiro->grupo->maxPessoa?></p>
														<p><?= $obRoteiro->convdata($obRoteiro->grupo->dataEmbarque,"mtn");?></p>
														<p><?= $obRoteiro->convdata($obRoteiro->grupo->dataChegada,"mtn");?></p>
													</div>
												</div>
											</div>
										</div>
									</div>
						</div>
					</div>
					
					
				</div><!-- tab menu strat -->

				<div class="package-tab-menu">
					<ul class="package-tab-menu" role="tablist" id="tab7">
					<li role="presentation"  class="active"><a href="#fotos" aria-controls="fotos" role="tab" data-toggle="tab">Fotos</a>
						</li>	
					<li role="presentation"><a href="#description" aria-controls="description" role="tab" data-toggle="tab">Sobre o Roteiro</a>
						</li>
						<li role="presentation"><a href="#itinerary" aria-controls="itinerary" role="tab" data-toggle="tab">Roteiro</a>
						</li>
						
						<li role="presentation"><a href="#video" aria-controls="video" role="tab" data-toggle="tab">Videos</a>
						</li>
						<li role="presentation"><a href="#reviews" aria-controls="reviews" role="tab" data-toggle="tab">Comentários (<?= count($obRoteiro->reviews)?>)</a>
						</li>
					</ul>
				</div><!-- tab menu end -->

				<!-- tab content start -->
				<div class="row">
					<!-- tabs content -->
					<div class="tab-content">
					<div role="tabpanel" class="tab-pane fade in active" id="fotos">
						<div class="row">
								<div class="col-md-12 col-sm-12">
									<div class="tour-description">
										<h4>Fotos</h4>
										<div class="grid-3 ">
										<?php foreach ($obRoteiro->photos as $key => $foto) {
											# code...
										?>
										<a href="img/fotos/<?=$foto->name?>">
										<div class="col-sm-<?=$key==0? '12' :'6'?> col-md-<?=$key==0? '6' :'3'?> grid-item">
										
										<figure>
										
											<img src="img/fotos/<?=$foto->name?>" alt="">
											
										</figure>

										</div>	
										</a>
										<?php }?>
									</div>
									</div>
								</div>
							</div>
							
						</div>
						<div role="tabpanel" class="tab-pane fade " id="description">
							
							<div class="row">
								<!-- left content -->
								<div class="col-md-12 col-sm-12">
									<div class="tour-description">
										
										<p><?=$obRoteiro->description?></p>
									</div>									
								</div><!-- left-content -->

								
							</div>
						</div>

						<div role="tabpanel" class="tab-pane fade" id="itinerary">
							<div class="row">
								<div class="col-md-12 col-sm-12">
									<div class="tour-description">
										
										<div class="main-timeline">
											<!-- single timeline -->
											<?php foreach ($obRoteiro->itineraryes as $key => $etinerario) {
												# code...
											?>
											<div class="timeline">
												<div class="timeline-content left">
													<span class="timeline-icon"><?= $etinerario->order?></span>
													<h4><?= $etinerario->title?></h4>
													<p><?= $etinerario->description?></p>
												</div>
											</div><!-- single timeline -->
											<? } ?>											
										</div>
									</div>
								</div>
							</div>
						</div>

						

						<!-- video tab content start -->
						<div role="tabpanel" class="tab-pane fade" id="video">
							<div class="row">
								<div class="col-md-12 col-sm-12">
									<div class="tour-description">
										<h4>Videos</h4>
										<?php 
										
										if(count($obRoteiro->videos) > 0){
											$video = $obRoteiro->videos[0];
											# code...
										?>
										<!-- Video -->
										<div class="tab-video-area video-bg" style="background: #37b721 url('img/packages/<?=$obRoteiro->image?>') no-repeat scroll center center/cover;">
											<div class="video-play-btn">
												<a href="<?=$video->name?>" class="video_iframe"><span><i class="fa fa-play"></i></span></a>
											</div>
										</div><!-- Video -->
										<?php } ?>
									</div>
								</div>
							</div>
						</div><!-- video tab content end -->

						<!-- video tab content start -->
						<div role="tabpanel" class="tab-pane fade" id="reviews">
							<div class="row">
								<div class="col-md-12 col-sm-12">
									<div class="tour-description">
										<h4>Últimos Comentários</h4>	
												<div class="blog-comments">
													
													<div class="comments-body">
													<?php foreach ($obRoteiro->reviews as $key => $review) {												
													?>
													<!-- Single Comments -->
													<div class="single-comments">
														<div class="main">
															<div class="head">
															<img src="img/reviews/<?=$review->photo?>" alt="#" />
																<h4><?=$review->name?></h4>
															</div>
															<div class="body">
																<p class="meta"><?=$obRoteiro->convdata($review->date,"mtnh")?></p>
																<p><?=$review->review?></p>
															</div>
														</div>
													</div><!-- Single Comments -->
													<?php } ?>													
													</div>
												</div><!--/ End Comments -->
											
											
												<!-- div class="comment-respond">
													<div class="comment-reply-title">
														<h3>Deixe seu comentário</h3>
													</div>
													<div class="comment-form">
														<form action="#" method="post" id="formReview" onsubmit="return enviarReview();">
															<div class="form-group">
																<label>Nome</label>
																<input name="name" type="text" class="form-control" placeholder="Nome *" required >
															</div>
															<div class="form-group">
																<label>Email</label>
																<input name="email" type="email" placeholder="email" class="form-control">
															</div>
															<div class="form-group">
																<label>Comentário</label>
																<textarea name="message" placeholder="Mensagem *" class="form-control" rows="4" required ></textarea>
															</div>
															<div class="form-group">
															<p class="text-danger text-center" id="dialog-review"></p>
															</div>
															<div class="full-widthfull-width">
																<input value="Submit"  type="submit" value="Enviar">
															</div>
															<input type="hidden" name="roteiro" value="<?=$obRoteiro->id?>"/>
														</form>
													</div>
												</div -->
											
										
									</div>
								</div>
							</div>
						</div><!-- video tab content end -->
					</div><!-- tabs content-->
				</div><!-- tab content end -->
			</div><!-- single package tab with details -->

			<!-- booking form start here -->
			<div class="col-md-4 col-sm-12">
				<aside>
					<div class="booking-form">
						<div class="booking-title">
							<h2>Compre Agora</h2>
							<h4>Restam apenas <span class="color-one"><?=$vagas?></span> Vagas!!!</h4>
						</div>
						<div class="form-group">
								<button type="button" onclick="window.location.href= 'admin/checkout.php?idGrupo=<?=$obRoteiro->grupo->id?>';" class="booking-confirm hvr-shutter-out-horizontal">COMPRAR</a>
							</div>
						
						<form action="#" method="post" id="formEmail" onsubmit="return enviarEmail();">
						<h5>Gostaria de nos enviar um email para mais informações sobre o Roteiro?</h5>
							<div class="form-group">
								<input type="text" class="form-control" name="nome" id="name" placeholder="Nome *" required>
							</div>
							<div class="form-group">
								<input type="email" class="form-control" name="email" id="confirm_email" placeholder="E-mail *" required>
							</div>
							<div class="form-group">
								<textarea name="message" id="message" cols="30" rows="10" class="form-control" placeholder="Mensagem*" required></textarea>
							</div>	
							<div class="form-group">
								<p class="text-danger text-center" id="dialog-message"></p>
							</div>							
							<div class="form-group">
								<button type="submit" class="booking-confirm hvr-shutter-out-horizontal">ENVIAR</button>
							</div>
							<input type="hidden" name="grupo" value="<?=$obRoteiro->grupo->nomePacote?>"/>
						</form>
					</div>
				</aside><!-- adverestment start here-->

				<div class="adding-form">
					<div class="addfor-bg">
						<div class="add-content">
							<h3>Alguma Dúvida?</h3>
							<p>Entre em contato conosco</p>
							<ul class="contact-for-add">
								<li><img src="images/icon/phone.png" alt="">61 3201-5116</li>
								<li><img src="images/icon/gmail.png" alt="">vendas@obrademariadf.com.br</li>
							</ul>
						</div>
					</div>
				</div><!-- adverestment start here-->
			</div><!-- booking form end here -->
		</div>
	</div>
</section>
<? include('include-footer-area.php');?>
<div class="to-top pos-rtive">
	<a href="#"><i class = "fa fa-angle-up"></i></a>
</div> <!-- Scroll to top jump button end-->
<? include('include-footer.php');?>
<script>
$(document).ready(function(){
	$("#do_like").click(function(){
		dolike(1);
	});
	$("#do_unlike").click(function(){
		dolike(-1);

	});

});

function showMessage(tipo,texto,tag){
	if(tipo == 'erro'){
		$(tag).removeClass('text-success').addClass('text-danger');
	}else{
		$(tag).removeClass('text-danger').addClass('text-success');
	}

	$(tag).html(texto);
}
function enviarEmail(){
	let form = $("#formEmail").serialize();
    $("#formEmail")[0].reset();
	//funcao de sucesso
	var funcSuccess = function(data) {
	if(data.code == 200){
		showMessage("sucesso", data.data.mensagem,"#dialog-message");
		
	}else{
	  showMessage("erro", data.data.mensagem,"#dialog-message");
	  //alert("Erro encontrado\n"+data.data.mensagem);
	}
	}
	//funcao de erro
	var funcDefaultError = function(erro) {
	  showMessage('erro','Erro ao enviar o email',"#dialog-message");

	}
	postJson('ajax/sentEmail.php',form,funcSuccess,funcDefaultError);
	return false;
}


function enviarReview(){
	let form = $("#formReview").serialize();
    $("#formReview")[0].reset();        
	//funcao de sucesso
	var funcSuccess = function(data) {
	if(data.code == 200){
		showMessage("sucesso", data.data.mensagem, "#dialog-review");
		
	}else{
	  showMessage("erro", data.data.mensagem, "#dialog-review");
	  //alert("Erro encontrado\n"+data.data.mensagem);
	}
	}
	//funcao de erro
	var funcDefaultError = function(erro) {
	  showMessage('erro','Erro ao enviar o review', "#dialog-review");

	}
	postJson('ajax/sentReview.php',form,funcSuccess,funcDefaultError);
	return false;
}

function dolike(value){
	var funcSuccess = function(data) {
	if(data.code == 200){
		//showMessage("sucesso", data.data.mensagem);
		$(".lbl_like").html(data.data.likes);
		$(".lbl_unlike").html(data.data.unlikes);
		makeStars(data.data.stars)
	}
	}
	//funcao de erro
	var funcDefaultError = function(erro) {
	  console.log(erro);
	}
	postJson('ajax/dolike.php',{"like":value,"roteiro":<?=$obRoteiro->id?>},funcSuccess,funcDefaultError);
}

function makeStars(stars){
	$("#star1").removeClass("fa-star").removeClass("fa-star-o");
	$("#star2").removeClass("fa-star").removeClass("fa-star-o");
	$("#star3").removeClass("fa-star").removeClass("fa-star-o");
	$("#star4").removeClass("fa-star").removeClass("fa-star-o");
	$("#star5").removeClass("fa-star").removeClass("fa-star-o");
	if(stars >=1){
		$("#star1").addClass("fa-star")
	}else{
		$("#star1").addClass("fa-star-o");
	}

	if(stars >=2){
		$("#star2").addClass("fa-star")
	}else{
		$("#star2").addClass("fa-star-o");
	}

	if(stars >=3){
		$("#star3").addClass("fa-star")
	}else{
		$("#star3").addClass("fa-star-o");
	}

	if(stars >=4){
		$("#star4").addClass("fa-star")
	}else{
		$("#star4").addClass("fa-star-o");
	}

	if(stars >=5){
		$("#star5").addClass("fa-star")
	}else{
		$("#star5").addClass("fa-star-o");
	}
}
</script>
</body>
</html>