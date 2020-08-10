<?php
$disRoteiro = new Roteiro();
$disReturn = $disRoteiro->getDescontosRandomicos(3);
if($disReturn){
?>
<!-- discount & deals section start here -->
<section class="section-paddings">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="section-title text-center">
					<h2>Oportunidades de descontos</h2>
					<p>Aproveite o melhor dos seus sonhos com preços inperdíveis</p>
				</div>
			</div>
		</div>
		<div class="row">
			<?php foreach ($disReturn as $key => $value) {
				# code...
			?>
			<!-- single dicount -->
			<div class="col-sm-6 col-md-4 single-item">
				<div class="single-discount-deal">
					<figure>
						<img src="img/packages/<?=$value->cardImage?>" alt="">
						<figcaption>
							<div class="offer-content">
								<div class="circle-offer">
									<p><?=$value->grupo->descontoAVista?>%
										<br>Off</p>
								</div>
								<div class="offer-details-2">
									<h3>Melhor Roteiro em <?=$value->grupo->local?></h3>
									<p><?= $value->grupo->duracao ?> dias <del><?= $value->grupo->moeda->cifrao." ".$disRoteiro->money($value->grupo->valorPacote,"atb") ?></del> <span><?= $value->grupo->moeda->cifrao." ".$disRoteiro->money($value->grupo->valorPacote - ($value->grupo->valorPacote*($value->grupo->descontoAVista/100)),"atb")  ?></span>
									</p>
								</div>
							</div>
							<div class="travel-book-btn">
								<a href="package.php?id=<?=$value->id?>" class="travel-booking-btn hvr-fade">Detalhes</a>
							</div>
						</figcaption>
					</figure>
				</div>
			</div><!--end single dicount -->
			<? }?>			
		</div>
	</div>
</section> <!-- discount & deals section end here here -->
<? }?>