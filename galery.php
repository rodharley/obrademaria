<?php 
include("admin/tupi.inicializar.php"); 
include('include-header.php');

$ob = new Galeria();
$ob->getById($_REQUEST['id']);

?>

<body>
<!-- Preloader -->
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>	
<?php include('include-menu.php'); ?>
<!-- blog breadcrumb version one strat here -->
<section class="breadcrumb-blog-version-one">
	<div class="single-bredcurms" style="background-image:url('admin/img/grupos/<?=$ob->grupo->imagemDestaque?>');">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<div class="bredcrums-content">
						<h2>Galeria</h2>
						<ul>
							<li><a href="index.php">Home</a></li>
							<li><a href="#">Galeria</a></li>
							<li class="active"><a href="#"><?= $ob->name?></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</section><!-- blog breadcrumb version one end here -->

<section class="section-paddings">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="section-title-version-2-black text-center">
					<h2><?=$ob->name?></h2>
					<p></p>
				</div>
			</div>
		</div>
		
		<div class="grid-3">
		<?php foreach ($ob->photos as $key => $item) {
			
			?>
			<a href="img/galery/<?=$item->name?>">
			<div class="<?= $item->type == 0 ? 'col-sm-12 col-md-6' : 'col-sm-6 col-md-3'?> grid-item">
				<figure>
					<img src="img/galery/<?=$item->nameThumb?>" alt="">
					<figcaption>
						
						<h4>Local <span><?=$ob->grupo->local?></span></h4>
						<h4>Descrição: <span><?=$item->description?></span></h4>
					</figcaption>
				</figure>
			</div> </a><!-- end single gallery -->
		<? }?>
			
		</div> <!-- gallery item end here -->
	</div>
</section> <!-- gallery section end here -->


<?php include('include-footer-area.php');?>

<div class="to-top pos-rtive">
	<a href="#"><i class = "fa fa-angle-up"></i></a>
</div> <!-- Scroll to top jump button end-->

 <?php include('include-footer.php');?>
</body>
</html>
