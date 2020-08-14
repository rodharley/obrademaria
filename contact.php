<?php 
include("admin/tupi.inicializar.php"); 
include('include-header.php');?>

<body>
<!-- Preloader -->
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>	
<?php include('include-menu.php'); ?>
<!-- blog breadcrumb version one strat here -->
<section class="breadcrumb-blog-version-one">
	<div class="single-bredcurms" style="background-image:url('images/bgimage/newsletter.png');">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<div class="bredcrums-content">
						<h2>Contato</h2>
						<ul>
							<li><a href="index.php">Home</a></li>
							<li class="active"><a href="contact.php">Contato</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</section><!-- blog breadcrumb version one end here -->

<!-- google map start  -->
<div class="map-wrapper">
	<div class="map-area">
		<div id="googleMap"></div>
	</div>
</div>


<?php include('include-footer-area.php');?>

<div class="to-top pos-rtive">
	<a href="#"><i class = "fa fa-angle-up"></i></a>
</div> <!-- Scroll to top jump button end-->
 <?php include('include-footer.php');?>
 <!-- google map api -->
 <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKq53gqv-hIiASlmMwfayTBUAcOJwueRw" type="text/javascript"></script>
 
    <!-- map js -->
    <script src="js/google-map.js"></script> 
</body>
</html>
