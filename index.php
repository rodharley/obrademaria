<?php
include("admin/tupi.inicializar.php"); 
$roteiro = new Roteiro();
$slide = new Slide();
$sliders = $slide->getSQL("select s.* from ag_slide s inner join ag_roteiro r on r.id = s.roteiro inner join ag_grupo g on g.id = r.grupo where s.publish = 1 order by g.dataEmbarque asc");
$continentes = $roteiro->getContinentesDispoiveis();
$roteirosPartida = $roteiro->pesquisar('','','',false,0,6);
?>
<?php include("include-header.php") ?>
<body> 
<!-- Preloader -->
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>	
<?php include('include-menu.php'); ?>
<!-- slider area start here -->
<?php if(count($sliders)>0){?>
<section class="slider-area">
	<div class="rev_slider_wrapper">
		<div id="rev_slider_1" class="rev_slider" style="display:none">
			<ul>
				<?php foreach ($sliders as $key => $item) {
					# code...
				?>
				<li data-autoplay="true" data-autoplayTimeout="5000" data-index="rs-301<?=$key?>" data-transition="fade" data-slotamount="7" data-hideafterloop="0" data-hideslideonmobile="off"  data-easein="default" data-easeout="default" data-masterspeed="1000"  data-thumb="images/slider/slider-back-01.jpg"  data-rotate="0"  data-fstransition="fade" data-fsmasterspeed="1000" data-fsslotamount="7" data-saveperformance="off"  data-title="Intro" data-param1="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">
					<div class="slider-overlay"></div>
					<!-- MAIN IMAGE -->
					<img src="img/slider/<?= $item->image ?>" alt="Sky" class="rev-slidebg">
					<!-- BEGIN BASIC TEXT LAYER -->
					<!-- LAYER NR. 1 -->
					<div class="tp-caption sfr font-extra-bold tp-resizeme letter-space-4 title-line-1" data-x="['center', 'center', 'center', 'center']" data-hoffset="0" data-y="center" data-voffset="-150" data-frames='[{"delay":0,"speed":3000,"frame":"0","from":"x:[175%];y:0px;z:0;rX:0;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:1;","mask":"x:[-100%];y:0;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]' style="z-index: 6; font-size:40px; color:#fff; text-transform:capitalize; font-family:'Poppins', sans-serif; white-space: nowrap; font-weight:400;"><?= $item->title?>
					</div>

					<!-- LAYER NR. 2 -->
					<div class="tp-caption sfr font-extra-bold tp-resizeme letter-space-4 title-line-2" data-x="center" data-hoffset="0" data-y="center" data-voffset="-80" data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","mask":"x:0px;y:[100%];s:inherit;e:inherit;","speed":2000,"to":"o:1;","delay":750,"ease":"Power4.easeInOut"},{"delay":"wait","speed":1000,"to":"y:[100%];","mask":"x:inherit;y:inherit;s:inherit;e:inherit;","ease":"Power2.easeInOut"}]' style="z-index: 6; color:#fff; text-transform:capitalize; font-family:'Poppins', sans-serif; white-space: nowrap;"><h1 style="font-weight:00; font-size:60px;"><?= $item->subTitle?></h1>
					</div>

					<!-- LAYER NR. 3 -->
					<div class="tp-caption font-lora sfb tp-resizeme letter-space-5  header-p1" data-x="center" data-hoffset="0" data-y="center" data-voffset="-5" data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","mask":"x:0px;y:[100%];s:inherit;e:inherit;","speed":2000,"to":"o:1;","delay":750,"ease":"Power4.easeInOut"},{"delay":"wait","speed":1000,"to":"y:[100%];","mask":"x:inherit;y:inherit;s:inherit;e:inherit;","ease":"Power2.easeInOut"}]' style="z-index: 7; font-size:16px; color:#fff; font-family:'Poppins', sans-serif; white-space: nowrap; font-weight:400;">
					<?= $item->description?>
					</div>
					

					<!-- LAYER NR. 5 -->
					<div class="tp-caption lfb tp-resizeme  header-btn" data-x="center" data-hoffset="0" data-y="center" data-voffset="120" data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","mask":"x:0px;y:[100%];s:inherit;e:inherit;","speed":2000,"to":"o:1;","delay":750,"ease":"Power4.easeInOut"},{"delay":"wait","speed":1000,"to":"y:[100%];","mask":"x:inherit;y:inherit;s:inherit;e:inherit;","ease":"Power2.easeInOut"}]' style="z-index: 8;"><a href="package.php?id=<?=$item->roteiro->id?>" class="travel-primary-btn hvr-fade"><?= $item->buttomText?></a>
					</div>
				</li>
				<?php }?>
				
			</ul>
		</div> <!-- end slider container -->
	</div> <!-- END end slider container wrapper -->
</section> <!-- slider area end here -->
<?php } ?>
<?php include('include-search.php')?>
<?php if(count($roteirosPartida)>0){ ?>
<section class="popular-packages pb-70 pt-100">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="section-title text-center">
					<h2>Roteiros mais próximos</h2>
					<p>Nossos Roteiros por ordem de partida</p>
				</div>
			</div>
		</div>
		<div class="row">
			<?php 
			foreach ($roteirosPartida as $key => $rp) {
				$stars = $rp->getNumberStars();
			?>
			<div class="col-md-4 col-sm-6">
				<div class="single-package">
					<div class="package-image">
						<a href="package.php?id=<?=$rp->id?>"><img src="img/packages/<?= $rp->cardImage?>" alt="">
						</a>
					</div>
					<div class="package-content">
						<h3><?= $rp->cardTitle?></h3>
						<p><?= $rp->cardDescription?> <span><?=$rp->grupo->moeda->cifrao." ".$roteiro->money($rp->grupo->getValorTotal(0),"atb")?></span>
						</p>
					</div>
					<div class="package-calto-action">
						<ul class="ct-action">
							<li><a href="package.php?id=<?=$rp->id?>" class="travel-booking-btn hvr-shutter-out-horizontal">Detalhes</a>
							</li>
							<li>
								<i class="fa <?=$stars >=1 ? 'fa-star' : 'fa-star-o';?>"></i>
								<i class="fa <?=$stars >=2 ? 'fa-star' : 'fa-star-o';?>"></i>
								<i class="fa <?=$stars >=3 ? 'fa-star' : 'fa-star-o';?>"></i>
								<i class="fa <?=$stars >=4 ? 'fa-star' : 'fa-star-o';?>"></i>
								<i class="fa <?=$stars >=5 ? 'fa-star' : 'fa-star-o';?>"></i>
							</li>
						</ul>
					</div>
				</div>
			</div> <!-- single package end -->
			<?php } ?>
			
		</div>
	</div>
</section> <!--end  popular packajge -->
<?php }?>

<?PHP include('include-discount.php');?>
<?php if(count($continentes) > 0){ ?>
<section class="section-paddings popular-country">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="section-title-version-2-black text-center">
					<h2>Roteiros Mais Populares</h2>
					<p>Separamos aqui os roteiros mais populares por continente para você.</p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="destination-tab-menu">
					<ul class="destination-menu" id="myTab2">
					<?php 
					foreach($continentes as $key => $c){					
					?>	
					<li  class="<?= $key == 0 ? 'active' : ''?>"><a href="#cont<?=$c->id?>" data-easein="fadeIn"><?=$c->continent?></a>
						</li>
					<? }?>
						
					</ul>
				</div><!-- tab menu end -->

				<div class="destination-countrys">
					<div class="tab-content" id="tab-content2">
						<!-- Asia tab content start -->
						<?php 
					foreach($continentes as $key => $c){	
						$roteiros = $roteiro->getByContinent($c->continent,4);				
					?>
						<div class="tab-pane <?= $key == 0 ? 'active' : ''?>" id="cont<?=$c->id?>">
							<div class="row">
							<?php foreach($roteiros as $key => $r){	?>
								<div class="col-sm-3 col-md-3 padding-bottom">
									<div class="single-country">
										<figure >
											<a href="package.php?id=<?=$r->id?>"><img src="img/packages/<?=$r->cardImage?>" alt="" class="img-responsive img-rounded">
											</a>
											<figcaption>
												<div class="city-name">
													<span><img src="images/icon/map.png" alt=""><?= $r->cardTitle ?></span>
													<ul class="tower-bridge">
														<li><?= $r->grupo->moeda->cifrao." ".$roteiro->money($r->grupo->getValorTotal(0),"atb")?></li>
														<li><?=$r->grupo->ano?></li>
													</ul>
												</div>
												<div class="travel-book-btn">
													<a href="package.php?id=<?=$r->id?>" class="travel-booking-btn hvr-shutter-out-horizontal">Detalhes</a>
												</div>
											</figcaption>
										</figure>
									</div>
								</div>	
							<?php }?>							
							</div>
						</div> 
					<?php } ?>						
					
					</div>
				</div> <!-- tab content end -->
			</div>
		</div>
	</div>
</section>
<?php }?>
<? include('include-countdown-area.php');?>
<? include('include-reviews-area.php');?>
<? include('include-galerys-area.php');?>
<? include('include-footer-area.php');?>

<div class="to-top pos-rtive">
	<a href="#"><i class = "fa fa-angle-up"></i></a>
</div><!-- Scroll to top-->    
    <?php include('include-footer.php') ?>
</body>
</html>
