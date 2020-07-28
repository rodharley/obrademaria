<?php
include("admin/tupi.inicializar.php"); 

$slide = new Slide();
$sliders = $slide->getRows();

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
					<div class="tp-caption lfb tp-resizeme  header-btn" data-x="center" data-hoffset="0" data-y="center" data-voffset="120" data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","mask":"x:0px;y:[100%];s:inherit;e:inherit;","speed":2000,"to":"o:1;","delay":750,"ease":"Power4.easeInOut"},{"delay":"wait","speed":1000,"to":"y:[100%];","mask":"x:inherit;y:inherit;s:inherit;e:inherit;","ease":"Power2.easeInOut"}]' style="z-index: 8;"><a href="#" class="travel-primary-btn hvr-fade"><?= $item->buttomText?></a>
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
<section class="section-paddings popular-country">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="section-title-version-2-black text-center">
					<h2>Most popular destinations</h2>
					<p>Lorem ipsum dolor sit amet consectetur adipiscing elit Etiam at ipsum at ligula vestibulum </p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="destination-tab-menu">
					<ul class="destination-menu" id="myTab2">
						<li  class="active"><a href="#asia" data-easein="fadeIn">Asia</a>
						</li>
						<li><a href="#europe" data-easein="fadeIn">Europe</a>
						</li>
						<li><a href="#america" data-easein="fadeIn">America</a>
						</li>
						<li><a href="#africa" data-easein="fadeIn">Africa</a>
						</li>
						<li><a href="#australia" data-easein="fadeIn">Australia</a>
						</li>
					</ul>
				</div><!-- tab menu end -->

				<div class="destination-countrys">
					<div class="tab-content" id="tab-content2">
						<!-- Asia tab content start -->
						<div class="tab-pane" id="asia">
							<div class="row">
								<div class="col-sm-3 col-md-3 padding-bottom">
									<div class="single-country">
										<figure>
											<a href="#"><img src="images/destination/pd1.jpg" alt="" class="img-responsive img-rounded">
											</a>
											<figcaption>
												<div class="city-name">
													<span><img src="images/icon/map.png" alt="">London, Eangland</span>
													<ul class="tower-bridge">
														<li>London Bridge</li>
														<li>3 Tours</li>
													</ul>
												</div>
												<div class="travel-book-btn">
													<a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
												</div>
											</figcaption>
										</figure>
									</div>
								</div>
								<div class="col-sm-3 col-md-3 padding-bottom">
									<div class="single-country">
										<figure>
											<a href="#"><img src="images/destination/pd2.jpg" alt="" class="img-responsive img-rounded">
											</a>
											<figcaption>
												<div class="city-name">
													<span><img src="images/icon/map.png" alt="">Rome, Italy</span>
													<ul class="tower-bridge">
														<li>Colosiam</li>
														<li>3 Tours</li>
													</ul>
												</div>
												<div class="travel-book-btn">
													<a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
												</div>
											</figcaption>
										</figure>
									</div>
								</div>

								<div class="col-sm-3 col-md-3 padding-bottom">
									<div class="single-country">
										<figure>
											<a href="#"><img src="images/destination/pd3.jpg" alt="" class="img-responsive img-rounded">
											</a>
											<figcaption>
												<div class="city-name">
													<span><img src="images/icon/map.png" alt="">Delhi, India</span>
													<ul class="tower-bridge">
														<li>India Gate</li>
														<li>3 Tours</li>
													</ul>
												</div>
												<div class="travel-book-btn">
													<a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
												</div>
											</figcaption>
										</figure>
									</div>
								</div>
								<div class="col-sm-3 col-md-3 padding-bottom">
									<div class="single-country">
										<figure>
											<a href="#"><img src="images/destination/pd4.jpg" alt="" class="img-responsive img-rounded">
											</a>
											<figcaption>
												<div class="city-name">
													<span><img src="images/icon/map.png" alt="">Paris, France</span>
													<ul class="tower-bridge">
														<li>Eiffel Tower</li>
														<li>3 Tours</li>
													</ul>
												</div>
												<div class="travel-book-btn">
													<a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
												</div>
											</figcaption>
										</figure>
									</div>
								</div>
							</div>
						</div> <!-- Asia tab content end -->

						<div class="tab-pane active" id="europe">
							<div class="row">
								<div class="col-sm-3 col-md-3 padding-bottom">
									<div class="single-country">
										<figure>
											<a href="#"><img src="images/destination/pd1.jpg" alt="" class="img-responsive img-rounded">
											</a>
											<figcaption>
												<div class="city-name">
													<span><img src="images/icon/map.png" alt="">Eangland, London</span>
													<ul class="tower-bridge">
														<li>Tower Bridge</li>
														<li>3 Tours</li>
													</ul>
												</div>
												<div class="travel-book-btn">
													<a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
												</div>
											</figcaption>
										</figure>
									</div>
								</div>
								<div class="col-sm-3 col-md-3 padding-bottom">
									<div class="single-country">
										<figure>
											<a href="#"><img src="images/destination/pd2.jpg" alt="" class="img-responsive img-rounded">
											</a>
											<figcaption>
												<div class="city-name">
													<span><img src="images/icon/map.png" alt="">Eangland, London</span>
													<ul class="tower-bridge">
														<li>Tower Bridge</li>
														<li>3 Tours</li>
													</ul>
												</div>
												<div class="travel-book-btn">
													<a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
												</div>
											</figcaption>
										</figure>
									</div>
								</div>

								<div class=" col-sm-3 col-md-3 padding-bottom">
									<div class="single-country">
										<figure>
											<a href="#"><img src="images/destination/pd3.jpg" alt="" class="img-responsive img-rounded">
											</a>
											<figcaption>
												<div class="city-name">
													<span><img src="images/icon/map.png" alt="">Eangland, London</span>
													<ul class="tower-bridge">
														<li>Tower Bridge</li>
														<li>3 Tours</li>
													</ul>
												</div>
												<div class="travel-book-btn">
													<a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
												</div>
											</figcaption>
										</figure>
									</div>
								</div>
								<div class="col-sm-3 col-md-3 padding-bottom">
									<div class="single-country">
										<figure>
											<a href="#"><img src="images/destination/pd4.jpg" alt="" class="img-responsive img-rounded">
											</a>
											<figcaption>
												<div class="city-name">
													<span><img src="images/icon/map.png" alt="">Eangland, London</span>
													<ul class="tower-bridge">
														<li>Tower Bridge</li>
														<li>3 Tours</li>
													</ul>
												</div>
												<div class="travel-book-btn">
													<a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
												</div>
											</figcaption>
										</figure>
									</div>
								</div>
							</div>
						</div><!-- europe tab content end -->

						<div class="tab-pane" id="america">
							<div class="row">
								<div class=" col-sm-3 col-md-3 padding-bottom">
									<div class="single-country">
										<figure>
											<a href="#"><img src="images/destination/pd1.jpg" alt="" class="img-responsive img-rounded">
											</a>
											<figcaption>
												<div class="city-name">
													<span><img src="images/icon/map.png" alt="">Eangland, London</span>
													<ul class="tower-bridge">
														<li>Tower Bridge</li>
														<li>3 Tours</li>
													</ul>
												</div>
												<div class="travel-book-btn">
													<a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
												</div>
											</figcaption>
										</figure>
									</div>
								</div>
								<div class=" col-sm-3 col-md-3 padding-bottom">
									<div class="single-country">
										<figure>
											<a href="#"><img src="images/destination/pd2.jpg" alt="" class="img-responsive img-rounded">
											</a>
											<figcaption>
												<div class="city-name">
													<span><img src="images/icon/map.png" alt="">Eangland, London</span>
													<ul class="tower-bridge">
														<li>Tower Bridge</li>
														<li>3 Tours</li>
													</ul>
												</div>
												<div class="travel-book-btn">
													<a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
												</div>
											</figcaption>
										</figure>
									</div>
								</div>

								<div class=" col-sm-3 col-md-3 padding-bottom">
									<div class="single-country">
										<figure>
											<a href="#"><img src="images/destination/pd3.jpg" alt="" class="img-responsive img-rounded">
											</a>
											<figcaption>
												<div class="city-name">
													<span><img src="images/icon/map.png" alt="">Eangland, London</span>
													<ul class="tower-bridge">
														<li>Tower Bridge</li>
														<li>3 Tours</li>
													</ul>
												</div>
												<div class="travel-book-btn">
													<a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
												</div>
											</figcaption>
										</figure>
									</div>
								</div>
								<div class="col-sm-3 col-md-3 padding-bottom">
									<div class="single-country">
										<figure>
											<a href="#"><img src="images/destination/pd4.jpg" alt="" class="img-responsive img-rounded">
											</a>
											<figcaption>
												<div class="city-name">
													<span><img src="images/icon/map.png" alt="">Eangland, London</span>
													<ul class="tower-bridge">
														<li>Tower Bridge</li>
														<li>3 Tours</li>
													</ul>
												</div>
												<div class="travel-book-btn">
													<a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
												</div>
											</figcaption>
										</figure>
									</div>
								</div>
							</div>
						</div> <!-- america tab content end-->

						<div class="tab-pane" id="africa">
							<div class="row">
								<div class="col-sm-3 col-md-3 padding-bottom">
									<div class="single-country">
										<figure>
											<a href="#"><img src="images/destination/pd1.jpg" alt="" class="img-responsive img-rounded">
											</a>
											<figcaption>
												<div class="city-name">
													<span><img src="images/icon/map.png" alt="">Eangland, London</span>
													<ul class="tower-bridge">
														<li>Tower Bridge</li>
														<li>3 Tours</li>
													</ul>
												</div>
												<div class="travel-book-btn">
													<a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
												</div>
											</figcaption>
										</figure>
									</div>
								</div>
								<div class="col-sm-3 col-md-3 padding-bottom">
									<div class="single-country">
										<figure>
											<a href="#"><img src="images/destination/pd2.jpg" alt="" class="img-responsive img-rounded">
											</a>
											<figcaption>
												<div class="city-name">
													<span><img src="images/icon/map.png" alt="">Eangland, London</span>
													<ul class="tower-bridge">
														<li>Tower Bridge</li>
														<li>3 Tours</li>
													</ul>
												</div>
												<div class="travel-book-btn">
													<a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
												</div>
											</figcaption>
										</figure>
									</div>
								</div>

								<div class="col-sm-3 col-md-3 padding-bottom">
									<div class="single-country">
										<figure>
											<a href="#"><img src="images/destination/pd3.jpg" alt="" class="img-responsive img-rounded">
											</a>
											<figcaption>
												<div class="city-name">
													<span><img src="images/icon/map.png" alt="">Eangland, London</span>
													<ul class="tower-bridge">
														<li>Tower Bridge</li>
														<li>3 Tours</li>
													</ul>
												</div>
												<div class="travel-book-btn">
													<a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
												</div>
											</figcaption>
										</figure>
									</div>
								</div>
								<div class=" col-sm-3 col-md-3 padding-bottom">
									<div class="single-country">
										<figure>
											<a href="#"><img src="images/destination/pd4.jpg" alt="" class="img-responsive img-rounded">
											</a>
											<figcaption>
												<div class="city-name">
													<span><img src="images/icon/map.png" alt="">Eangland, London</span>
													<ul class="tower-bridge">
														<li>Tower Bridge</li>
														<li>3 Tours</li>
													</ul>
												</div>
												<div class="travel-book-btn">
													<a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
												</div>
											</figcaption>
										</figure>
									</div>
								</div>
							</div>
						</div><!-- america tab content end-->

						<div class="tab-pane" id="australia">
							<div class="row">
								<div class="col-sm-3 col-md-3 padding-bottom">
									<div class="single-country">
										<figure>
											<a href="#"><img src="images/destination/pd1.jpg" alt="" class="img-responsive img-rounded">
											</a>
											<figcaption>
												<div class="city-name">
													<span><img src="images/icon/map.png" alt="">Eangland, London</span>
													<ul class="tower-bridge">
														<li>Tower Bridge</li>
														<li>3 Tours</li>
													</ul>
												</div>
												<div class="travel-book-btn">
													<a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
												</div>
											</figcaption>
										</figure>
									</div>
								</div>
								<div class=" col-sm-3 col-md-3 padding-bottom">
									<div class="single-country">
										<figure>
											<a href="#"><img src="images/destination/pd2.jpg" alt="" class="img-responsive img-rounded">
											</a>
											<figcaption>
												<div class="city-name">
													<span><img src="images/icon/map.png" alt="">Eangland, London</span>
													<ul class="tower-bridge">
														<li>Tower Bridge</li>
														<li>3 Tours</li>
													</ul>
												</div>
												<div class="travel-book-btn">
													<a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
												</div>
											</figcaption>
										</figure>
									</div>
								</div>
  
								<div class="col-sm-3 col-md-3 padding-bottom">
									<div class="single-country">
										<figure>
											<a href="#"><img src="images/destination/pd3.jpg" alt="" class="img-responsive img-rounded">
											</a>
											<figcaption>
												<div class="city-name">
													<span><img src="images/icon/map.png" alt="">Eangland, London</span>
													<ul class="tower-bridge">
														<li>Tower Bridge</li>
														<li>3 Tours</li>
													</ul>
												</div>
												<div class="travel-book-btn">
													<a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
												</div>
											</figcaption>
										</figure>
									</div>
								</div>
								<div class="col-sm-3 col-md-3 padding-bottom">
									<div class="single-country">
										<figure>
											<a href="#"><img src="images/destination/pd4.jpg" alt="" class="img-responsive img-rounded">
											</a>
											<figcaption>
												<div class="city-name">
													<span><img src="images/icon/map.png" alt="">Eangland, London</span>
													<ul class="tower-bridge">
														<li>Tower Bridge</li>
														<li>3 Tours</li>
													</ul>
												</div>
												<div class="travel-book-btn">
													<a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
												</div>
											</figcaption>
										</figure>
									</div>
								</div>
							</div>
						</div> <!-- australia tab content end-->
					</div>
				</div> <!-- tab content end -->
			</div>
		</div>
	</div>
</section>
<section class="popular-packages pb-70 pt-100">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="section-title text-center">
					<h2>Our most popular packges</h2>
					<p>Lorem ipsum dolor sit amet consectetur adipiscing elit Etiam at ipsum at ligula vestibulum sodales Sed luctus orci vel nibh aliquam laoreet Aenean accumsan </p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4 col-sm-6">
				<div class="single-package">
					<div class="package-image">
						<a href="#"><img src="images/packages/1.jpg" alt="">
						</a>
					</div>
					<div class="package-content">
						<h3>Dubai – All Stunning Places</h3>
						<p>4 Days, 5 Nights Start From <span>$500</span>
						</p>
					</div>
					<div class="package-calto-action">
						<ul class="ct-action">
							<li><a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
							</li>
							<li>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
							</li>
						</ul>
					</div>
				</div>
			</div> <!-- single package end -->

			<div class="col-md-4 col-sm-6">
				<div class="single-package">
					<div class="package-image">
						<a href="#"><img src="images/packages/2.jpg" alt="">
						</a>
					</div>
					<div class="package-content">
						<h3>Thailand – All Stunning Places</h3>
						<p>4 Days, 5 Nights Start From <span>$500</span>
						</p>
					</div>
					<div class="package-calto-action">
						<ul class="ct-action">
							<li><a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
							</li>
							<li>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
							</li>
						</ul>
					</div>
				</div>
			</div> <!-- single package end -->

			<div class="col-md-4 col-sm-6">
				<div class="single-package">
					<div class="package-image">
						<a href="#"><img src="images/packages/3.jpg" alt="">
						</a>
					</div>
					<div class="package-content">
						<h3>England – All Stunning Places</h3>
						<p>4 Days, 5 Nights Start From <span>$500</span>
						</p>
					</div>
					<div class="package-calto-action">
						<ul class="ct-action">
							<li><a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
							</li>
							<li>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
							</li>
						</ul>
					</div>
				</div>
			</div> <!-- single package end -->

			<div class="col-md-4 col-sm-6">
				<div class="single-package">
					<div class="package-image">
						<a href="#"><img src="images/packages/4.jpg" alt="">
						</a>
					</div>
					<div class="package-content">
						<h3>Italy – All Stunning Places</h3>
						<p>4 Days, 5 Nights Start From <span>$500</span>
						</p>
					</div>
					<div class="package-calto-action">
						<ul class="ct-action">
							<li><a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
							</li>
							<li>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
							</li>
						</ul>
					</div>
				</div>
			</div> <!-- single package end -->

			<div class="col-md-4 col-sm-6">
				<div class="single-package">
					<div class="package-image">
						<a href="#"><img src="images/packages/5.jpg" alt="">
						</a>
					</div>
					<div class="package-content">
						<h3>Brazil – All Stunning Places</h3>
						<p>4 Days, 5 Nights Start From <span>$500</span>
						</p>
					</div>
					<div class="package-calto-action">
						<ul class="ct-action">
							<li><a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
							</li>
							<li>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
							</li>
						</ul>
					</div>
				</div>
			</div> <!-- single package end -->

			<div class="col-md-4 col-sm-6">
				<div class="single-package">
					<div class="package-image">
						<a href="#"><img src="images/packages/6.jpg" alt="">
						</a>
					</div>
					<div class="package-content">
						<h3>India – All Stunning Places</h3>
						<p>4 Days, 5 Nights Start From <span>$500</span>
						</p>
					</div>
					<div class="package-calto-action">
						<ul class="ct-action">
							<li><a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Book Now</a>
							</li>
							<li>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
							</li>
						</ul>
					</div>
				</div>
			</div> <!-- single package end -->
		</div>
	</div>
</section> <!--end  popular packajge -->

<section class="countdown count-down-bg image-bg-padding-100">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12-col-xs-12">
				<div class="count-down-titile">
					<h3>Special Tour in May, Discover <span class="color-one">Thailand</span> for 50 <br> Customers with <span class="color-two">Discount 30%</span> </h3>
				</div>
				<div class="count-timer text-center">
					<div class="time-wrapper">
						<p>It’s limited seating! Hurry up</p>
						<div class="timer">
							<div data-countdown="2018/12/15"></div>
						</div>
					</div>
				</div>
				<div class="buy-now text-center">
					<a href="#" class="travel-primary-btn hvr-fade">Buy Now</a>
				</div>
			</div>
		</div>
	</div>
</section>  <!--end  countdown -->

<section class="pb-70 pt-100">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="section-title text-center">
					<h2>Most popular destination</h2>
					<p>Lorem ipsum dolor sit amet consectetur adipiscing elit Etiam at ipsum at ligula vestibulum sodales Sed luctus orci vel nibh aliquam laoreet Aenean accumsan </p>
				</div>
			</div>
		</div>
		<div class="destination-slider-active owl-carousel">
			<div class="single-destination">
				<figure>
					<a href="#"><img src="images/destination/1.jpg" alt="">
					</a>
					<figcaption>
						<a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Read More</a>
					</figcaption>
				</figure>
				<div class="des-city">
					<a href="#"><i class="fa fa-map-marker"></i>Sydney, Australia</a>
					<h4>Opera House <span>3 Tours</span></h4>
				</div>
			</div> <!-- single popular destination  end-->

			<div class="single-destination">
				<figure>
					<a href="#"><img src="images/destination/2.jpg" alt="">
					</a>
					<figcaption>
						<a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Read More</a>
					</figcaption>
				</figure>
				<div class="des-city">
					<a href="#"><i class="fa fa-map-marker"></i>London, Eangland</a>
					<h4>Tower Bridge<span>5 Tours</span></h4>
				</div>
			</div> <!-- single popular destination  end-->

			<div class="single-destination">
				<figure>
					<a href="#"><img src="images/destination/3.jpg" alt="">
					</a>
					<figcaption>
						<a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Read More</a>
					</figcaption>
				</figure>
				<div class="des-city">
					<a href="#"><i class="fa fa-map-marker"></i>Paris, France</a>
					<h4>Eiffel Tower<span>4 Tours</span></h4>
				</div>
			</div> <!-- single popular destination  end-->

			<div class="single-destination">
				<figure>
					<a href="#"><img src="images/destination/4.jpg" alt="">
					</a>
					<figcaption>
						<a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Read More</a>
					</figcaption>
				</figure>
				<div class="des-city">
					<a href="#"><i class="fa fa-map-marker"></i>New york, USA</a>
					<h4>Statue Of Liberty<span>3 Tours</span></h4>
				</div>
			</div> <!-- single popular destination  end-->

			<div class="single-destination">
				<figure>
					<a href="#"><img src="images/destination/5.jpg" alt="">
					</a>
					<figcaption>
						<a href="#" class="travel-booking-btn hvr-shutter-out-horizontal">Read More</a>
					</figcaption>
				</figure>
				<div class="des-city">
					<a href="#"><i class="fa fa-map-marker"></i>Agra, India</a>
					<h4>Tajmahal<span>5 Tours</span></h4>
				</div>
			</div> <!-- single popular destination  end-->
		</div>
	</div>
</section> <!-- end popular destination-->

<section class="trabble-bg image-bg-padding-100">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="section-title-white text-center">
					<h2>Why Choose travlestar</h2>
					<p>Lorem ipsum dolor sit amet consectetur adipiscing elit Etiam at ipsum at ligula vestibulum sodales Sed luctus orci vel nibh aliquam laoreet Aenean accumsan </p>
				</div>
			</div>
		</div>
		<div class="row">
			<!-- single travel start -->
			<div class="col-md-4 col-sm-6">
				<div class="single-travel">
					<div class="media">
						<div class="media-left media-middle travel-number">
							<span>01.</span>
						</div>
						<div class="media-body travel-content">
							<h4>Travel Arrangements</h4>
							<p>Lorem ipsum dolor sit amet consect adiu piscing elit sed diam nonum euismo tincidunt ut.</p>
						</div>
					</div>
				</div>
			</div> <!-- single travel end -->

			<div class="col-md-4 col-sm-6">
				<div class="single-travel">
					<div class="media">
						<div class="media-left media-middle travel-number">
							<span>02.</span>
						</div>
						<div class="media-body travel-content">
							<h4>Cheap Flights</h4>
							<p>Lorem ipsum dolor sit amet consect adiu piscing elit sed diam nonum euismo tincidunt ut.</p>
						</div>
					</div>
				</div>
			</div> <!-- single travel end -->

			<div class="col-md-4 col-sm-6">
				<div class="single-travel">
					<div class="media">
						<div class="media-left media-middle travel-number">
							<span>03.</span>
						</div>
						<div class="media-body travel-content">
							<h4>Hand-picked tours</h4>
							<p>Lorem ipsum dolor sit amet consect adiu piscing elit sed diam nonum euismo tincidunt ut.</p>
						</div>
					</div>
				</div>
			</div> <!-- single travel end -->

			<div class="col-md-4 col-sm-6">
				<div class="single-travel">
					<div class="media">
						<div class="media-left media-middle travel-number">
							<span>04.</span>
						</div>
						<div class="media-body travel-content">
							<h4>Privet Guide</h4>
							<p>Lorem ipsum dolor sit amet consect adiu piscing elit sed diam nonum euismo tincidunt ut.</p>
						</div>
					</div>
				</div>
			</div> <!-- single travel end -->

			<div class="col-md-4 col-sm-6">
				<div class="single-travel">
					<div class="media">
						<div class="media-left media-middle travel-number">
							<span>05.</span>
						</div>
						<div class="media-body travel-content">
							<h4>Special Activities</h4>
							<p>Lorem ipsum dolor sit amet consect adiu piscing elit sed diam nonum euismo tincidunt ut.</p>
						</div>
					</div>
				</div>
			</div> <!-- single travel end -->

			<div class="col-md-4 col-sm-6">
				<div class="single-travel">
					<div class="media">
						<div class="media-left media-middle travel-number">
							<span>06.</span>
						</div>
						<div class="media-body travel-content">
							<h4>Best Price Guarantee</h4>
							<p>Lorem ipsum dolor sit amet consect adiu piscing elit sed diam nonum euismo tincidunt ut.</p>
						</div>
					</div>
				</div>
			</div> <!-- single travel end -->
		</div>
	</div>
</section> <!-- choose trabble end here -->

<!-- guide and Expert Advice strat -->
<section class="section-paddings">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="section-title text-center">
					<h2>Travel guide and Expert Advice</h2>
					<p>Lorem ipsum dolor sit amet consectetur adipiscing elit Etiam at ipsum at ligula vestibulum sodales Sed luctus orci vel nibh aliquam laoreet Aenean accumsan </p>
				</div>
			</div>
		</div>
		<div class="row">
			<!-- single travel blog-->
			<div class="col-md-4 col-sm-6 phone-layout-s">
				<div class="single-travel-blog">
					<div class="blog-image">
						<a href="#"><img src="images/blog/1.jpg" alt="">
						</a>
					</div>
					<div class="blog-content">
						<div class="blog-meta">
							<div class="post-date">
								<span><i class="fa fa-calendar"></i> 12 Sep, 2018</span>
							</div>
							<ul class="post-social">
								<li><a href="#"><i class="fa fa-comments"></i>3</a>
								</li>
								<li><a href="#"><i class="fa fa-heart-o"></i>43</a>
								</li>
							</ul>
						</div>
						<div class="blog-post-content">
							<h4>Tips for taking a long-term trip with kids.</h4>
							<p>Lorem ipsum dolor sit amet consepctetur adipiscing elit Etiam at ipsum at ligula vestibulum sodales Sed luctus.</p>
							<a href="#">Read More <i class="fa fa-angle-right"></i></a>
						</div>
					</div>
				</div>
			</div> <!--end single travel guide & security-->

			<div class="col-md-4 col-sm-6 phone-layout-s">
				<div class="single-travel-blog">
					<div class="blog-image">
						<a href="#"><img src="images/blog/2.jpg" alt="">
						</a>
					</div>
					<div class="blog-content">
						<div class="blog-meta">
							<div class="post-date">
								<span><i class="fa fa-calendar"></i> 12 Aug, 2018</span>
							</div>
							<ul class="post-social">
								<li><a href="#"><i class="fa fa-comments"></i>3</a>
								</li>
								<li><a href="#"><i class="fa fa-heart-o"></i>43</a>
								</li>
							</ul>
						</div>
						<div class="blog-post-content">
							<h4>Tips for taking a long-term trip with kids.</h4>
							<p>Lorem ipsum dolor sit amet consepctetur adipiscing elit Etiam at ipsum at ligula vestibulum sodales Sed luctus.</p>
							<a href="#">Read More <i class="fa fa-angle-right"></i></a>
						</div>
					</div>
				</div>
			</div> <!--end single travel guide & security-->

			<div class="col-md-4 col-sm-6 phone-layout-s">
				<div class="single-travel-blog">
					<div class="blog-image">
						<a href="#"><img src="images/blog/3.jpg" alt="">
						</a>
					</div>
					<div class="blog-content">
						<div class="blog-meta">
							<div class="post-date">
								<span><i class="fa fa-calendar"></i> 12 Jul, 2018</span>
							</div>
							<ul class="post-social">
								<li><a href="#"><i class="fa fa-comments"></i>3</a>
								</li>
								<li><a href="#"><i class="fa fa-heart-o"></i>43</a>
								</li>
							</ul>
						</div>
						<div class="blog-post-content">
							<h4>Tips for taking a long-term trip with kids.</h4>
							<p>Lorem ipsum dolor sit amet consepctetur adipiscing elit Etiam at ipsum at ligula vestibulum sodales Sed luctus.</p>
							<a href="#">Read More <i class="fa fa-angle-right"></i></a>
						</div>
					</div>
				</div>
			</div> <!-- single travel guide & security end-->
		</div>
	</div>
</section> <!--End guide and Expert Advice strat -->

<!-- testimonial area start here -->
<section class="testimonial-area image-bg-padding-100">
	<div class="testimonial-wrapper">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="section-title-white text-center mbt-100">
						<h2>What travellers Say About Us</h2>
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
								<p>Lorem ipsum dolor sit amet, consecteituer adipiscing eluit, sed diapm nonummy nibhu euismod tincidunt ut laoreet dolor you magna aliquam erat volutpat. Ut wisi enim adefra miniumyp veniam, quis nostrud exerci tation ullavolutpat.</p>
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

<div class="section-paddings incredible-places">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="section-title text-center">
					<h2>Incredible Places</h2>
					<p>Lorem ipsum dolor sit amet consectetur adipiscing elit Etiam at ipsum at ligula vestibulum sodales Sed luctus orci vel nibh aliquam laoreet Aenean accumsan</p>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-6">
			<div class="single-place">
				<figure>
					<a href="#"><img src="images/place/1.jpg" alt="">
					</a>
					<figcaption>
						<h4>Place <span>Eiffel Tower</span></h4>
						<h4>Caption By: <span>Michel Jusi</span></h4>
					</figcaption>
				</figure>
			</div>
		</div> <!-- end single place -->

		<div class="col-md-4 col-sm-6">
			<div class="single-place">
				<figure>
					<a href="#"><img src="images/place/2.jpg" alt="">
					</a>
					<figcaption>
						<h4>Place <span>China Town</span></h4>
						<h4>Caption By: <span>Daniel Baci</span></h4>
					</figcaption>
				</figure>
			</div>
		</div> <!-- end single place -->

		<div class="col-md-4 col-sm-6">
			<div class="single-place">
				<figure>
					<a href="#"><img src="images/place/3.jpg" alt="">
					</a>
					<figcaption>
						<h4>Place <span>England Bridge</span></h4>
						<h4>Caption By: <span>John Adam</span></h4>
					</figcaption>
				</figure>
			</div>
		</div> <!-- end single place -->

		<div class="col-md-4 col-sm-6">
			<div class="single-place">
				<figure>
					<a href="#"><img src="images/place/4.jpg" alt="">
					</a>
					<figcaption>
						<h4>Place <span>Eiffel Tower</span></h4>
						<h4>Caption By: <span>Michel Jusi</span></h4>
					</figcaption>
				</figure>
			</div>
		</div> <!-- end single place -->

		<div class="col-md-4 col-sm-6">
			<div class="single-place">
				<figure>
					<a href="#"><img src="images/place/5.jpg" alt="">
					</a>
					<figcaption>
						<h4>Place <span>China Town</span></h4>
						<h4>Caption By: <span>Daniel Baci</span></h4>
					</figcaption>
				</figure>
			</div>
		</div> <!-- end single place -->

		<div class="col-md-4 col-sm-6">
			<div class="single-place">
				<figure>
					<a href="#"><img src="images/place/3.jpg" alt="">
					</a>
					<figcaption>
						<h4>Place <span>England Bridge</span></h4>
						<h4>Caption By: <span>John Adam</span></h4>
					</figcaption>
				</figure>
			</div>
		</div> <!-- end single place -->
	</div>
</div> <!-- incredible place end here -->


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

<section class="section-paddings">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="section-title text-center">
					<h2>Out trusted partners</h2>
					<p>Lorem ipsum dolor sit amet consectetur adipiscing elit Etiam at ipsum at ligula vestibulum sodales Sed luctus orci vel nibh aliquam laoreet Aenean accumsan </p>
				</div>
			</div>
		</div>
		<div class="row">
			<!-- partners images -->
			<div class="partner-slider-active owl-carousel">
				<div class="single-pertner">
					<div class="partner-image">
						<a href="#"><img src="images/partner/1.png" alt="">
						</a>
					</div>
				</div>
				<div class="single-pertner">
					<div class="partner-image">
						<a href="#"><img src="images/partner/2.png" alt="">
						</a>
					</div>
				</div>
				<div class="single-pertner">
					<div class="partner-image">
						<a href="#"><img src="images/partner/3.png" alt="">
						</a>
					</div>
				</div>
				<div class="single-pertner">
					<div class="partner-image">
						<a href="#"><img src="images/partner/4.png" alt="">
						</a>
					</div>
				</div>
				<div class="single-pertner">
					<div class="partner-image">
						<a href="#"><img src="images/partner/5.png" alt="">
						</a>
					</div>
				</div>
			</div>
		</div>
		<!-- partners images -->
		<div class="row">
			<!-- partners images -->
			<div class="partner-slider-active owl-carousel">
				<div class="single-pertner">
					<div class="partner-image">
						<a href="#"><img src="images/partner/1.png" alt="">
						</a>
					</div>
				</div>
				<div class="single-pertner">
					<div class="partner-image">
						<a href="#"><img src="images/partner/2.png" alt="">
						</a>
					</div>
				</div>
				<div class="single-pertner">
					<div class="partner-image">
						<a href="#"><img src="images/partner/3.png" alt="">
						</a>
					</div>
				</div>
				<div class="single-pertner">
					<div class="partner-image">
						<a href="#"><img src="images/partner/4.png" alt="">
						</a>
					</div>
				</div>
				<div class="single-pertner">
					<div class="partner-image">
						<a href="#"><img src="images/partner/5.png" alt="">
						</a>
					</div>
				</div>
			</div>
		</div>	<!-- end partners images -->
	</div>
</section> <!--end partner section -->

<footer class="footer-area">
	<div class="container">
		<div class="row">
			<!-- footer left -->
			<div class="col-md-3 col-sm-6">
				<div class="single-footer">
					<div class="footer-title">
						<a href="#"><img src="images/logo.png" alt="">
						</a>
					</div>
					<div class="footer-left">
						<div class="footer-logo">
							<p>Lorem ipsum dolor sit amet conset ctetur adipiscin elit Etiam at ipsum at ligula vestibulum sodales.</p>
						</div>
						<ul class="footer-contact">
							<li><img class="map" src="images/icon/map.png" alt="">Seventh Avenue New York</li>
							<li><img class="map" src="images/icon/phone.png" alt="">+123-456-7890</li>
							<li><img class="map" src="images/icon/gmail.png" alt="">info@yourcompany.com</li>
						</ul>
					</div>
				</div>
			</div> <!-- footer left -->

			<div class="col-md-3 col-sm-6">
				<div class="single-footer">
					<div class="single-recent-post">
						<div class="footer-title">
							<h3>Recent News</h3>
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
						<h3>Destination</h3>
					</div>
					<ul class="footer-gallery">
						<li>
							<a href="#">
								<div class="image-overlay">
									<img src="images/destination/1.jpg" alt="">
									<div class="overly-city">
										<span>Austrila</span>
									</div>
								</div>
							</a>
						</li>
						<li>
							<a href="#">
								<div class="image-overlay">
									<img src="images/destination/2.jpg" alt="">
									<div class="overly-city">
										<span>England</span>
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
						<h3>Quick Contact</h3>
					</div>
					<div class="footer-contact-form">
						<form action="#">
							<ul class="footer-form-element">
								<li>
									<input type="text" name="email" id="email" placeholder="" value="Email Address" onblur="if(this.value==''){this.value='Email Address'}" onfocus="if(this.value=='Email Address'){this.value=''}">
								</li>
								<li>
									<textarea name="message" id="message" cols="30" rows="10" placeholder="Message"></textarea>
								</li>
								<li>
									<button>Send</button>
								</li>
							</ul>
						</form>
					</div>
					<div class="footer-social-media">
						<div class="social-footer-title">
							<h3>Follow Us</h3>
						</div>
						<ul class="footer-social-link">
							<li class="facebook"><a href="#"><i class="fa fa-facebook"></i></a>
							</li>
							<li class="twitter"><a href="#"><i class="fa fa-twitter"></i></a>
							</li>
							<li class="linkedin"><a href="#"><i class="fa fa-linkedin"></i></a>
							</li>
							<li class="gplus"><a href="#"><i class="fa fa-google-plus"></i></a>
							</li>
							<li class="youtube"><a href="#"><i class="fa fa-youtube-play"></i></a>
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
						<p>Copyright &copy; 2018 Trabble By <a href="#"><span>SylTheme</span></a></p>
					</div>
				</div>
				<div class="col-md-7">
					<ul class="payicon pull-right">
						<li>We Accept</li>
						<li><img src="images/payicon01.png" alt=""></li>
						<li><img src="images/payicon02.png" alt=""></li>
						<li><img src="images/payicon03.png" alt=""></li>
						<li><img src="images/payicon04.png" alt=""></li>
						<li><img src="images/payicon05.png" alt=""></li>
						<li><img src="images/payicon06.png" alt=""></li>
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
