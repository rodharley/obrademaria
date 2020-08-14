<?php
$reviewsReview  = new Review();
$reviews = $reviewsReview->getRandomicos(5);
if(count($reviews)>0){
?>
<!-- testimonial area start here -->
<section class="testimonial-area image-bg-padding-100">
	<div class="testimonial-wrapper">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="section-title-white text-center mbt-100">
						<h2>Experiências e Testemunhos de Nossos Peregrinos</h2>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="custom-width text-center">
					<!-- start top media -->
					<div class="top-testimonial-image row slick-pagination">
						<div class="carousel-images slider-nav">
                            <?php foreach($reviews as $key => $rev){ ?>
                            <div>
								<img src="img/reviews/<?=$rev->photo?>" alt="1" class="img-circle">
                            </div>
                            <? } ?>							
						</div>
					</div>
				</div><!-- end top media images -->

				<div class="block-text row">
					<div class="carousel-text slider-for col-sm-8 col-sm-offset-2">
                    <?php foreach($reviews as $key => $rev){ ?>
                        <div class="testimonial-message">
							<div class="message">
								<h4 class="color-two"><?= $rev->local?></h4>
								<p><?= $rev->review?></p>
							</div>
							
								<div class="client-bio">
								<h4><?= $rev->name?></h4>
								<span><?= $reviewsReview->convdata($rev->date,"mtnh")?></span>
							</div>
						</div> <!-- client testimonial end -->
                            <? } ?>	    
                    
                    

						
					</div> <!-- /.block-text -->
				</div>
			</div>
		</div>
	</div>
</section> <!-- testimonial area end here -->
<?php } ?>