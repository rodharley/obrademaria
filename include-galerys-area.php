<?php
$obGaleria = new Galeria();
$galerias = $obGaleria->getGaleriasRandomicas(9);
if(count($galerias) > 0){
?>
<div class="section-paddings incredible-places">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="section-title text-center">
					<h2>Galeria de Fotos</h2>
					<p>Acompanhe aqui as fotos de nossas viagens</p>
				</div>
			</div>
		</div>
		<?php foreach ($galerias as $key => $g) {
			# code...
		?>
		<div class="col-md-4 col-sm-6">
			<div class="single-place">
				<figure>
					<a href="#"><img src="img/galery/<?=$g->photos[0]->name?>" alt=""/>
					</a>
					<figcaption>
						<h4><?=$g->name?></h4>		
						<a href="galery.php?id=<?=$g->id?>" class="travel-booking-btn ">Entrar</a>				
					</figcaption>
				</figure>
			</div>
		</div> <?php } ?>
		
	</div>
</div>
<?php } ?>