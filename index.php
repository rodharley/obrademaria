<?php
include("admin/tupi.inicializar.php"); 
$roteiro = new Roteiro();
$slide = new Slide();
$sliders = $slide->getRows();
$continentes = $roteiro->getContinentesDispoiveis();
$roteirosPartida = $roteiro->pesquisar('',null,false,0,6);
?>
<!DOCTYPE html>
<html class="no-js" lang="bt-br">
<?php include("header.php") ?>
<body> 
<!-- Preloader -->
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>	
<?php include('menu.php'); ?>
<!-- slider area start here -->
<section class="slider-area">
	<div class="rev_slider_wrapper">
		<div id="rev_slider_1" class="rev_slider" style="display:none">
			<ul>
				<?php foreach ($sliders as $key => $item) {
					# code...
				?>
				<li data-index="rs-301<?=$key?>" data-transition="fade" data-slotamount="7" data-hideafterloop="0" data-hideslideonmobile="off"  data-easein="default" data-easeout="default" data-masterspeed="1000"  data-thumb="images/slider/slider-back-01.jpg"  data-rotate="0"  data-fstransition="fade" data-fsmasterspeed="1000" data-fsslotamount="7" data-saveperformance="off"  data-title="Intro" data-param1="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">
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
					<div class="tp-caption lfb tp-resizeme  header-btn" data-x="center" data-hoffset="0" data-y="center" data-voffset="120" data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","mask":"x:0px;y:[100%];s:inherit;e:inherit;","speed":2000,"to":"o:1;","delay":750,"ease":"Power4.easeInOut"},{"delay":"wait","speed":1000,"to":"y:[100%];","mask":"x:inherit;y:inherit;s:inherit;e:inherit;","ease":"Power2.easeInOut"}]' style="z-index: 8;"><a href="packages.php?id=<?=$item->roteiro->id?>" class="travel-primary-btn hvr-fade"><?= $item->buttomText?></a>
					</div>
				</li>
				<?php }?>
				
			</ul>
		</div> <!-- end slider container -->
	</div> <!-- END end slider container wrapper -->
</section> <!-- slider area end here -->

<section class="tabbased-search-area">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="tabbable-menu">
					<ul class="tab-menu" id="myTab1">
						<li class="active"><h6><a href="#hotels" data-easein="fadeIn">Hotels<img src="images/icon/hotel.png" alt=""></a></h6>
						</li>
						<li><h6><a href="#tour" data-easein="fadeIn">Tour<img src="images/icon/tour.png" alt=""></a></h6>
						</li>
						<li><h6><a href="#flights" data-easein="fadeIn">Flights<img src="images/icon/fly.png" alt=""></a></h6>
						</li>
						<li><h6><a href="#vehicles" data-easein="fadeIn">Vehicles<img src="images/icon/car.png" alt=""></a></h6>
						</li>
						<li><h6><a href="#ship"  data-easein="fadeIn">Ship<img src="images/icon/ship.png" alt=""></a></h6>
						</li>
					</ul>
				</div> <!-- tab menu end here -->

				<!-- tab content strat here -->
				<div class="tab-content" id="tab-content1">
					<div class="tab-pane active" id="hotels">
						<div class="hotels-form">
							<form action="#" method="post">
								<div class="hotel-input-2 input-b">
									<input type="text" name="s" id="keyword" class="hotel-input-first" placeholder="Type Keyword">
								</div>
								<div class="hotel-input-4 input-b">
									<select id='standard1' name='standard' class='custom-select'>
										<option value=''>Select a Location</option>
										<option value='Us'>America</option>
										<option value='Canda'>Canada</option>
										<option value='london'>London</option>
										<option value='france'>Paris</option>
										<option value='bd'>Bangladesh</option>
									</select>
								</div> 
								<div class="hotel-input-1 input-s">
									<input type="text" name="s" id="datepicker" class="hotel-input-first" placeholder="Check-In">
								</div>
								<div class="hotel-input-1 input-s">
									<input type="text" name="s" id="datepicker1" class="hotel-input-first" placeholder="Check-out">
								</div>
								<div class="hotel-input-1 input-s">
									<input type="number" name="s" id="number" class="hotel-input-first" placeholder="Guest">
								</div>
								<div class="hotel-input-1 input-s">
									<input type="text" name="s" id="budget" class="hotel-input-first" placeholder="Budget">
								</div>
								<div class="searc-btn-7">
									<button type="submit">Search</button>
								</div>
							</form>
						</div>
					</div> <!-- hotel form end here -->

					<div class="tab-pane" id="tour">
						<div class="hotels-form">
							<form action="#" method="post">
								<div class="hotel-input-4-23 input-b">
									<input type="text" name="s" id="keyword2" class="hotel-input-first" placeholder="Type Keyword">
								</div>
								<div class="hotel-input-4-23 input-b">
									<select id='standard2' name='standard' class='custom-select'>
										<option value=''>Select a Location</option>
										<option value='Us'>America</option>
										<option value='Canda'>Canada</option>
										<option value='london'>London</option>
										<option value='france'>Paris</option>
										<option value='bd'>Bangladesh</option>
									</select>
								</div>
								<div class="hotel-input-4-23 input-s">
									<input type="text" name="s" id="datepicker2" class="hotel-input-first" placeholder="Check-In Date">
								</div>
								<div class="hotel-input-4-23 input-s">
									<input type="number" name="s" id="number1" class="hotel-input-first" placeholder="Number of Guest">
								</div>
								<div class="searc-btn-7">
									<button type="submit">Search</button>
								</div>
							</form>
						</div>
					</div> <!-- hotel form end here -->

					<div class="tab-pane" id="flights">
						<div class="flights-form">
							<form action="#" method="post">
								<div class="tour-input-20 input-b">
									<select id='standard3' name='standard' class='custom-select'>
										<option value=''>Origin City or airport</option>
										<option value='Us'>America</option>
										<option value='Canda'>Canada</option>
										<option value='london'>London</option>
										<option value='france'>Paris</option>
										<option value='bd'>Bangladesh</option>
									</select>
								</div>
								<div class="tour-input-20 input-b">
									<select id='standard4' name='standard' class='custom-select'>
										<option value=''>Destination City</option>
										<option value='Us'>America</option>
										<option value='Canda'>Canada</option>
										<option value='london'>London</option>
										<option value='france'>Paris</option>
										<option value='bd'>Bangladesh</option>
									</select>
								</div>
								<div class="tour-input-15 input-s">
									<input type="text" name="s" id="datepicker3" class="hotel-input-first" placeholder="Daparture date">
								</div>
								<div class="tour-input-15 input-s">
									<input type="text" name="s" id="datepicker4" class="hotel-input-first" placeholder="Return date">
								</div>
								<div class="tour-input-15 input-b">
									<select id='nosearch' name='standard' class='custom-select'>
										<option value=''>Economy</option>
										<option value='Economy'>Economy</option>
										<option value='Business'>Business</option>
									</select>
								</div>
								<div class="tour-input-7-5 input-s input-xm">
									<select id='nosearch1' name='standard' class='custom-select'>
										<option value=''>Adult</option>
										<option value='1'>1</option>
										<option value='2'>2</option>
										<option value='3'>3</option>
										<option value='4'>4</option>
										<option value='5'>5</option>
										<option value='6'>6</option>
										<option value='7'>7</option>
										<option value='8'>8</option>
										<option value='9'>9</option>
										<option value='10'>10</option>
									</select>
								</div>
								<div class="tour-input-7-5  input-s input-xm">
									<select id='nosearch2' name='standard' class='custom-select'>
										<option value=''>Kids</option>
										<option value='1'>1</option>
										<option value='2'>2</option>
										<option value='3'>3</option>
										<option value='4'>4</option>
										<option value='5'>5</option>
										<option value='6'>6</option>
										<option value='7'>7</option>
										<option value='8'>8</option>
										<option value='9'>9</option>
										<option value='10'>10</option>
									</select>
								</div>
								<div class="searc-btn-7 flights-search-btn">
									<button type="submit">Search</button>
								</div>
							</form>
						</div>
					</div> <!-- flights form start here -->

					<div class="tab-pane" id="vehicles">
						<div class="hotels-form">
							<form action="#" method="post">
								<div class="hotel-input-4-23  input-s">
									<input type="text" name="s" id="pickupdate" class="hotel-input-first" placeholder="Pickup Date & time">
								</div>
								<div class="hotel-input-4-23 input-s">
									<input type="number" name="s" id="hours" class="hotel-input-first" placeholder="Hours">
								</div>
								<div class="hotel-input-4-23 input-s">
									<input type="text" name="s" id="pickup" class="hotel-input-first" placeholder="Pickup Location">
								</div>
								<div class="hotel-input-4-23 input-s">
									<input type="text" name="s" id="location" class="hotel-input-first" placeholder="Drop Location">
								</div>
								<div class="searc-btn-7">
									<button type="submit">Search</button>
								</div>
							</form>
						</div>
					</div> <!-- vehicles form end here -->

					<div class="tab-pane" id="ship">
						<div class="hotels-form">
							<form action="#" method="post">
								<div class="hotel-input-4-23 input-s">
									<input type="text" name="s" id="shippickupdate" class="hotel-input-first" placeholder="Pickup Date & time">
								</div>
								<div class="hotel-input-4-23 input-s">
									<input type="number" name="s" id="time" class="hotel-input-first" placeholder="Hours">
								</div>
								<div class="hotel-input-4-23 input-s">
									<input type="text" name="s" id="name" class="hotel-input-first" placeholder="Pickup Location">
								</div>
								<div class="hotel-input-4-23 input-s">
									<input type="text" name="s" id="drop-location" class="hotel-input-first" placeholder="Drop Location">
								</div>
								<div class="searc-btn-7">
									<button type="submit">Search</button>
								</div>
							</form>
						</div>
					</div><!-- ship form end here -->
				</div><!-- tab content end -->
			</div>
		</div>
	</div>
</section> <!-- header tab based search area end-->

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
						<p><?= $rp->cardDescription?> <span><?=$rp->grupo->moeda->cifrao." ".$roteiro->money($rp->grupo->valorPacote,"atb")?></span>
						</p>
					</div>
					<div class="package-calto-action">
						<ul class="ct-action">
							<li><a href="package.php?id=<?=$rp->id?>" class="travel-booking-btn hvr-shutter-out-horizontal">Compre Agora</a>
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
										<figure>
											<a href="#"><img src="img/packages/<?=$r->cardImage?>" alt="" class="img-responsive img-rounded">
											</a>
											<figcaption>
												<div class="city-name">
													<span><img src="images/icon/map.png" alt=""><?= $r->cardTitle ?></span>
													<ul class="tower-bridge">
														<li><?= $r->cardDescription?></li>
														<li><?=$r->grupo->ano?></li>
													</ul>
												</div>
												<div class="travel-book-btn">
													<a href="package.php?id=<?=$r->id?>" class="travel-booking-btn hvr-shutter-out-horizontal">Compre Agora</a>
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


<section class="countdown count-down-bg image-bg-padding-100">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12-col-xs-12">
				<div class="count-down-titile">
					<h3>Viagem Especial em <span class="color-one">Terra Santa</span> Promocional <br> com <span class="color-two">20% de desconto</span> </h3>
				</div>
				<div class="count-timer text-center">
					<div class="time-wrapper">
						<p>Vagas limitadas! Corra e Garanta o seu</p>
						<div class="timer">
							<div data-countdown="2021/03/20"></div>
						</div>
					</div>
				</div>
				<div class="buy-now text-center">
					<a href="package.php" class="travel-primary-btn hvr-fade">Compre Agora</a>
				</div>
			</div>
		</div>
	</div>
</section>  <!--end  countdown -->

<!-- testimonial area start here -->
<section class="testimonial-area image-bg-padding-100">
	<div class="testimonial-wrapper">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="section-title-white text-center mbt-100">
						<h2>O que nossos peregrinos contam sobre nós</h2>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="custom-width text-center">
					<!-- start top media -->
					<div class="top-testimonial-image row slick-pagination">
						<div class="carousel-images slider-nav">
							<div>
								<img src="images/client/1.jpg" alt="1" class="img-circle">
							</div>
							<div>
								<img src="images/client/2.jpg" alt="3" class="img-circle">
							</div>
							<div>
								<img src="images/client/3.jpg" alt="2" class="img-circle">
							</div>

							<div>
								<img src="images/client/2.jpg" alt="2" class="img-circle">
							</div>
							<div>
								<img src="images/client/6.jpg" alt="2" class="img-circle">
							</div>
						</div>
					</div>
				</div><!-- end top media images -->

				<div class="block-text row">
					<div class="carousel-text slider-for col-sm-8 col-sm-offset-2">
						<div class="testimonial-message">
							<div class="message">
								<p>A Obra de Maria DF nos proporcionou a melhor viagem das nossas vidas, Muito obrigado!!</p>
							</div>
							<div class="rating">
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
							</div>
							<div class="client-bio">
								<h4>Maria Helena</h4>
								<span>São Paulo - Brasil</span>
							</div>
						</div> <!-- client testimonial end -->

						<div class="testimonial-message">
							<div class="message">
								<p>Lorem ipsum dolor sit amet, consecteituer adipiscing eluit, sed diapm nonummy nibhu euismod tincidunt ut laoreet dolor you magna aliquam erat volutpat. Ut wisi enim adefra miniumyp veniam, quis nostrud.</p>
							</div>
							<div class="rating">
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
							</div>
							<div class="client-bio">
								<h4>Daniel Baci</h4>
								<span>Thailand Trip Travelers</span>
							</div>
						</div> <!-- client testimonial end -->

						<div class="testimonial-message">
							<div class="message">
								<p>Lorem ipsum dolor sit amet, consecteituer adipiscing eluit, sed diapm nonummy nibhu euismod tincidunt ut laoreet dolor you magna aliquam erat volutpat. Ut wisi enim adefra miniumyp veniam, quis nostrud.</p>
							</div>
							<div class="rating">
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
							</div>
							<div class="client-bio">
								<h4>John Doe</h4>
								<span>USA Trip Travelers</span>
							</div>
						</div> <!-- client testimonial end -->

						<div class="testimonial-message">
							<div class="message">
								<p>Lorem ipsum dolor sit amet, consecteituer adipiscing eluit, sed diapm nonummy nibhu euismod tincidunt ut laoreet dolor you magna aliquam erat volutpat. Ut wisi enim adefra miniumyp veniam, quis nostrud.</p>
							</div>
							<div class="rating">
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
							</div>
							<div class="client-bio">
								<h4>Jhonthan Smith</h4>
								<span>London Trip Travelers</span>
							</div>
						</div>	<!-- client testimonial end -->

						<div class="testimonial-message">
							<div class="message">
								<p>Lorem ipsum dolor sit amet, consecteituer adipiscing eluit, sed diapm nonummy nibhu euismod tincidunt ut laoreet dolor you magna aliquam erat volutpat. Ut wisi enim adefra miniumyp veniam, quis nostrud.</p>
							</div>
							<div class="rating">
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
							</div>
							<div class="client-bio">
								<h4>Jhonthan Smith</h4>
								<span>London Trip Travelers</span>
							</div>
						</div> <!-- client testimonial end -->

						<div class="testimonial-message">
							<div class="message">
								<p>Lorem ipsum dolor sit amet, consecteituer adipiscing eluit, sed diapm nonummy nibhu euismod tincidunt ut laoreet dolor you magna aliquam erat volutpat. Ut wisi enim adefra miniumyp veniam, quis nostrud</p>
							</div>
							<div class="rating">
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
							</div>
							<div class="client-bio">
								<h4>Jhonthan Smith</h4>
								<span>London Trip Travelers</span>
							</div>
						</div> <!-- client testimonial end -->
					</div> <!-- /.block-text -->
				</div>
			</div>
		</div>
	</div>
</section> <!-- testimonial area end here -->


<section class="subscribe-area subscribe-bg image-bg-padding-100">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="section-title-white-2 text-center mbt-100">
					<h2>Join Our Subscribe List</h2>
				</div>
			</div>
		</div>
		<div class="row">
			<!-- subscribe form start here -->
			<div class="col-md-6 col-md-offset-3">
				<div class="subscribe-form">
					<form action="#" method="post">
						<div class="serach-form">
							<input type="text" name="search" id="search" placeholder="" value="Enter Your Email To subscribe" onblur="if(this.value==''){this.value='Enter Your Email To subscribe'}" onfocus="if(this.value=='Enter Your Email To subscribe'){this.value=''}">
						</div>
						<div class="search-btn">
							<button type="button">Send</button>
						</div>
					</form>
				</div>
			</div> <!-- subscribe form end here -->
		</div>
	</div>
</section> <!-- subscribe area end here -->


<footer class="footer-area">
	<div class="container">
		<div class="row">
			<!-- footer left -->
			<div class="col-md-3 col-sm-6">
				<div class="single-footer">
					<div class="footer-title">
						<a href="#"><img src="img/obra-logo-branco.png" alt="">
						</a>
					</div>
					<div class="footer-left">
						<div class="footer-logo">
							<p>Lorem ipsum dolor sit amet conset ctetur adipiscin elit Etiam at ipsum at ligula vestibulum sodales.</p>
						</div>
						<ul class="footer-contact">
							<li><img class="map" src="images/icon/map.png" alt="">SRTVS 701, Bloco II, Sala 208, Ed. Chateaubriand</li>
							<li><img class="map" src="images/icon/phone.png" alt="">61 3201-5116 | 61 98352-0475</li>
							<li><img class="map" src="images/icon/gmail.png" alt="">vendas@obrademariadf.com.br</li>
						</ul>
					</div>
				</div>
			</div> <!-- footer left -->

			<div class="col-md-3 col-sm-6">
				<div class="single-footer">
					<div class="single-recent-post">
						<div class="footer-title">
							<h3>Posts Recentes</h3>
						</div>
						<ul class="recent-post">
							<li>
								<a href="#">
									<div class="post-thum">
										<img src="images/blog/f4.jpg" alt="" class="img-rounded">
									</div>
									<div class="post-content">
										<p>A Clean Website Gives More Experience To The Visitors.
										</p>
										<span>12 July, 2018</span>
									</div>
								</a>
							</li>
							<li>
								<a href="#">
									<div class="post-thum">
										<img src="images/blog/f5.jpg" alt="" class="img-rounded">
									</div>
									<div class="post-content">
										<p>A Clean Website Gives More Experience To The Visitors.
										</p>
										<span>12 July, 2018</span>
									</div>
								</a>
							</li>
							<li>
								<a href="#">
									<div class="post-thum">
										<img src="images/blog/f6.jpg" alt="" class="img-rounded">
									</div>
									<div class="post-content">
										<p>A Clean Website Gives More Experience To The Visitors.
										</p>
										<span>12 July, 2018</span>
									</div>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>	<!-- footer latest news -->

			<!-- footer destination -->
			<div class="col-md-3 col-sm-6">
				<div class="single-footer">
					<div class="footer-title">
						<h3>Destinos</h3>
					</div>
					<ul class="footer-gallery">
						<li>
							<a href="#">
								<div class="image-overlay">
									<img src="img/packages/SANTO SEPULCRO 1.jpg" alt="">
									<div class="overly-city">
										<span>Terra Santa</span>
									</div>
								</div>
							</a>
						</li>
						<li>
							<a href="#">
								<div class="image-overlay">
									<img src="img/packages/DSC_0190.png" alt="">
									<div class="overly-city">
										<span>Inglaterra</span>
									</div>
								</div>
							</a>
						</li>
						<li>
							<a href="#">
								<div class="image-overlay">
									<img src="images/destination/3.jpg" alt="">
									<div class="overly-city">
										<span>France</span>
									</div>
								</div>
							</a>
						</li>
						<li>
							<a href="#">
								<div class="image-overlay">
									<img src="images/destination/4.jpg" alt="">
									<div class="overly-city">
										<span>America</span>
									</div>
								</div>
							</a>
						</li>
					</ul>
				</div>
			</div>	<!-- footer destination -->

			<!-- footer contact -->
			<div class="col-md-3 col-sm-6 f-phone-responsive">
				<div class="single-footer">
					<div class="footer-title">
						<h3>Entre em Contato</h3>
					</div>
					<div class="footer-contact-form">
						<form action="#">
							<ul class="footer-form-element">
								<li>
									<input type="text" name="email" id="email" placeholder="" value="Email" onblur="if(this.value==''){this.value='Email Address'}" onfocus="if(this.value=='Email Address'){this.value=''}">
								</li>
								<li>
									<textarea name="message" id="message" cols="30" rows="10" placeholder="Mensagem"></textarea>
								</li>
								<li>
									<button>Enviar</button>
								</li>
							</ul>
						</form>
					</div>
					<div class="footer-social-media">
						<div class="social-footer-title">
							<h3>siga-nos</h3>
						</div>
						<ul class="footer-social-link">
							<li class="facebook"><a href="https://www.facebook.com/ObraDeMariaDf/"><i class="fa fa-facebook"></i></a>
							</li>
							<li class="twitter"><a href="https://www.instagram.com/obrademariadf/"><i class="fa fa-instagram"></i></a>
							</li>
						</ul>
					</div>
				</div>
			</div>	<!-- footer contact -->
		</div>

		<div class="row">
			<div class="footer-bottom">
				<div class="col-md-5">
					<div class="copyright">
						<p>Copyright &copy; 2020 Criado por <a href="#"><span>Obra de Maria DF</span></a></p>
					</div>
				</div>
				<div class="col-md-7">
					<ul class="payicon pull-right">
						<li>Nós Aceitamos</li>
						<li><img src="images/payicon02.png" alt=""></li>
						<li><img src="images/payicon03.png" alt=""></li>
						
						<li><img src="images/payicon05.png" alt=""></li>
						
					</ul>
				</div>
			</div>
		</div>
	</div>
</footer> <!-- end footer -->

<div class="to-top pos-rtive">
	<a href="#"><i class = "fa fa-angle-up"></i></a>
</div><!-- Scroll to top-->    
    <?php include('footer.php') ?>
</body>
</html>
