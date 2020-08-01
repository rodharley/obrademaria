<?php
include("admin/tupi.inicializar.php");
$menusite = 1;
$pagina = 1;
if(isset($_REQUEST['pagina'])){
	$pagina = $_REQUEST['pagina'];
}
$obRoteiro = new Roteiro();
$total = $obRoteiro->pesquisar(isset($_REQUEST['termo']) ? $_REQUEST['termo'] : '', isset($_REQUEST['ano']) ? $_REQUEST['ano'] : '', isset($_REQUEST['local']) ? $_REQUEST['local'] : '',true);
$paginador = $obRoteiro->paginar($total,$pagina);
$rs = $obRoteiro->pesquisar(isset($_REQUEST['termo']) ? $_REQUEST['termo'] : '', isset($_REQUEST['ano']) ? $_REQUEST['ano'] : '',isset($_REQUEST['local']) ? $_REQUEST['local'] : '',false,$paginador['primeiroRegistro'],$paginador['quantidadePorPagina']);
?>
<?php include("include-header.php") ?>
<body>
<!-- Preloader -->
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>	   
<?php include('include-menu.php');?>

<section class="breadcrumb-blog-version-one">
	<div class="single-bredcurms" style="background-image:url('images/bgimage/footer1.jpg');">
	   <div class="container">
	   
		   <div class="row">
			   <div class="col-sm-12 col-md-12">
				 <div class="bredcrums-content">
					 <h2></h2>
					 <ul>
						
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
</section><!-- blog breadcrumb version one end here -->
<?php include('include-search.php')?>
<!-- popular destination strat -->
<section class="blog-contents-version-one pt-100 pb-70 popular-packages">
	<div class="container">
		<div class="row">
			<?php foreach ($rs as $key => $r) {
				# code...
			?>
			<div class="col-md-4 col-sm-6">
				<div class="single-package">
					<div class="package-image" style="min-height: 300px;">
						<a href="#"><img src="img/packages/<?=$r->cardImage?>" alt="">
						</a>
					</div>
					<div class="package-content">
						<h3><?=$r->cardTitle?></h3>
						<p><?=$r->grupo->duracao?> dias A Partir de  <span><?=$r->grupo->moeda->cifrao?> <?=$obRoteiro->money($r->grupo->getValorTotal(0),"atb")?></span>
						</p>
					</div>
					<div class="package-calto-action">
						<ul class="ct-action">
							<li><a href="package.php?id=<?= $r->id?>" class="travel-booking-btn hvr-shutter-out-horizontal">Compre Agora</a>
							</li>
							<li>
								<?= $r->getStarsHtml()?>
							</li>
						</ul>
					</div>
				</div>
			</div><!--end single package -->
			<?php }?>
			
		</div>
				<?php if($paginador['totalPaginas'] > 1){ ?>
		<div class="row">
			<div class="col-sm-12 text-center">
				<ul class="pagination">
					<li class="active"><a href="#">1 <span class="sr-only">(current)</span></a>
					</li>
					<li><a href="#">2</a>
					</li>
					<li><a href="#">3</a>
					</li>
				</ul>
			</div><!-- pagination end here -->
		</div>
				<?php } ?>
	</div>
</section><!-- single popular destination  end-->

<? include('include-footer-area.php');?>

<div class="to-top pos-rtive">
	<a href="#"><i class = "fa fa-angle-up"></i></a>
</div><!--End Scroll to top-->
    <?php include('include-footer.php');?>
</body>
</html>
