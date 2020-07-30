<?php
include("admin/tupi.inicializar.php"); 
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
<?php include("header.php");?>
<body>
<!-- Preloader -->
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>	  
<?php include('menu.php'); ?>
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
							   
								<i class="fa <?=$stars >=1 ? 'fa-star' : 'fa-star-o';?>"></i>
								<i class="fa <?=$stars >=2 ? 'fa-star' : 'fa-star-o';?>"></i>
								<i class="fa <?=$stars >=3 ? 'fa-star' : 'fa-star-o';?>"></i>
								<i class="fa <?=$stars >=4 ? 'fa-star' : 'fa-star-o';?>"></i>
								<i class="fa <?=$stars >=5 ? 'fa-star' : 'fa-star-o';?>"></i>
							
							 </span> (<?= count($obRoteiro->reviews)?> Reviews)
						</li>
					
						<li><?= $obRoteiro->grupo->moeda->cifrao.' '.$obRoteiro->money($obRoteiro->grupo->getValorTotal(0),"atb")?></li>
					</ul>
					<?php if(count($obRoteiro->photos) > 0){ ?>
					<div class="package-features-image">
						<img src="img/fotos/<?=$obRoteiro->photos[0]->name?>" alt="" class="img-responsove border-raduis-3">
					</div>
					<?php }?>
				</div><!-- tab menu strat -->

				<div class="package-tab-menu">
					<ul class="package-tab-menu" role="tablist" id="tab7">
						<li role="presentation" ><a href="#description" aria-controls="description" role="tab" data-toggle="tab">Descrição</a>
						</li>
						<li role="presentation"><a href="#itinerary" aria-controls="itinerary" role="tab" data-toggle="tab">Etinerário</a>
						</li>
						<li role="presentation" class="active"><a href="#fotos" aria-controls="fotos" role="tab" data-toggle="tab">Fotos</a>
						</li>
						<li role="presentation"><a href="#video" aria-controls="video" role="tab" data-toggle="tab">Videos</a>
						</li>
						<li role="presentation"><a href="#reviews" aria-controls="reviews" role="tab" data-toggle="tab">Reviews (<?= count($obRoteiro->reviews)?>)</a>
						</li>
					</ul>
				</div><!-- tab menu end -->

				<!-- tab content start -->
				<div class="row">
					<!-- tabs content -->
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane fade in active" id="description">
							
							<div class="row">
								<!-- left content -->
								<div class="col-md-7 col-sm-7">
									<div class="tour-description">
										<h4>Descrição do Roteiro</h4>
										<p><?=$obRoteiro->description?></p>
									</div>									
								</div><!-- left-content -->

								<!-- right content -->
								<div class="col-md-5 col-sm-5">
									<div class="additional-info">
										<div class="info-title">
											<h4>Informaçoes Adicionais</h4>
											<div class="row">
												<div class="col-md-7 col-sm-7">
													<div class="info-list">
														<p><span><i class="fa fa-map-marker"></i></span>Local</p>
														<p><span><i class="fa fa-clock-o"></i></span>Duração</p>
														<p><span><i class="fa fa-user"></i></span>Idade Mín.</p>
														<p><span><i class="fa fa-users"></i></span>Máx. de Pessoas</p>
														<p><span><i class="fa fa-plane"></i></span>Embrarque</p>
														<p><span><i class="fa fa-calendar-check-o"></i></span>Chegada</p>
														
													</div>
												</div>
												<div class="col-md-5 col-sm-5">
													<div class="info-details">
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
								</div><!-- right content -->
							</div>
						</div>

						<div role="tabpanel" class="tab-pane fade" id="itinerary">
							<div class="row">
								<div class="col-md-12 col-sm-12">
									<div class="tour-description">
										<h4>Etinerário do Roteiro</h4>
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

						<div role="tabpanel" class="tab-pane fade" id="fotos">
							<div class="row">
								<div class="col-md-12 col-sm-12">
									<div class="tour-description">
										<h4>Fotos</h4>
										<div class="grid-3 ">
										<?php foreach ($obRoteiro->photos as $key => $foto) {
											# code...
										?>
										<div class="col-sm-<?=$key==0? '12' :'6'?> col-md-<?=$key==0? '6' :'3'?> grid-item">
										<figure>
											<img src="img/fotos/<?=$foto->name?>" alt="">
											
										</figure>
										</div>	
										<?php }?>
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
										<h4>Reviews</h4>
										<?php foreach ($obRoteiro->reviews as $key => $review) {
												
											?>
										<p><?= $review->review?></p>
										<?php } ?>
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
<? include('footer-area.php');?>
<div class="to-top pos-rtive">
	<a href="#"><i class = "fa fa-angle-up"></i></a>
</div> <!-- Scroll to top jump button end-->
<? include('footer.php');?>
</body>
</html>
