<a id="aobra">
<footer class="footer-area">
	<div class="container">
		<div class="row">
			<!-- footer left -->
			<div class="col-md-6 col-sm-12">
				<div class="single-footer">
					<div class="footer-title">
						<a href="#"><img src="img/obra-logo-branco.png" alt="" style="width: 30%;">
						</a>
					</div>
					<div class="footer-left">
						<div class="footer-logo">
							<p>Mais que Viagens. Encontros com Deus.</p>
						</div>
						<ul class="footer-contact">
							<li><img class="map" src="images/icon/map.png" alt="">SRTVS 701, Bloco II, Sala 208, Ed. Chateaubriand</li>
							<li><img class="map" src="images/icon/phone.png" alt="">61 3201-5116 | 61 98352-0475</li>
							<li><img class="map" src="images/icon/gmail.png" alt="">brasilia@obrademaria.com.br</li>
						</ul>
					</div>
				</div>
			</div> <!-- footer left -->

			

			<!-- footer destination -->
			<?php
				$footerRoteiro = new Roteiro();
				$footerRoteiros = $footerRoteiro->getRoteirosRandomicos(4);
			?>
			<div class="col-md-3 col-sm-12">
				<div class="single-footer">
					<div class="footer-title">
						<h3>Destinos</h3>
					</div>
					<ul class="footer-gallery">
						<?php foreach ($footerRoteiros as $key => $value) {
							# code...
						?>
						<li>
							<a href="package.php?id=<?=$value->id?>">
								<div class="image-overlay">
									<img src="img/packages/<?=$value->cardImage?>" alt="">
									<div class="overly-city">
										<span><?= $value->grupo->local?></span>
									</div>
								</div>
							</a>
						</li>
						<? }?>
						
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
						<form action="#" method="post" id="footerForm" onsubmit="return footerEnviarEmail();" >
							<ul class="footer-form-element">
								<li>
									<input type="text" name="email" id="email" placeholder="Email *" required>
								</li>
								<li>
									<textarea name="message" id="message" cols="30" rows="10" placeholder="Mensagem *" required></textarea>
								</li>
								<li>
									<button type="submit"><i class="fa" id="footer-submit-icon"></i> Enviar</button>
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
</footer>