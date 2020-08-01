<?php
$cdRoteiro = new Roteiro();
$return = $cdRoteiro->getCountDown();
if($return){
?>
<section class="countdown count-down-bg image-bg-padding-100">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12-col-xs-12">
				<div class="count-down-titile">
					<h3>Viagem Especial para <span class="color-one"><?=$cdRoteiro->grupo->local?></span> Promocional <br> <span class="color-two"><?= $cdRoteiro->cardTitle?></span> </h3>
				</div>
				<div class="count-timer text-center">
					<div class="time-wrapper">
						<p>Vagas limitadas! Corra e Garanta o seu</p>
						<div class="timer">
							<div data-countdown="<?= str_replace("-","/",$cdRoteiro->grupo->dataEmbarque)?>"></div>
						</div>
					</div>
				</div>
				<div class="buy-now text-center">
					<a href="package.php?id=<?=$cdRoteiro->id?>" class="travel-primary-btn hvr-fade">Compre Agora</a>
				</div>
			</div>
		</div>
	</div>
</section>
<? }?>