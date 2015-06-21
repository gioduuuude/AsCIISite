<div id = "carouselContent" class = "container">
	<div id="myCarousel" class="carousel slide" data-ride="carousel"> 
		<!-- Indicators -->
		<ol class="carousel-indicators">
			<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
			<li data-target="#myCarousel" data-slide-to="1"></li>
		</ol>
		<div class="carousel-inner">
			<div class="item active"> <img src="<?php echo $this->webroot; ?>img/slide1.jpg" alt="First slide">
				<div class="container">
					<div class="carousel-caption">
						<h1>AsCII is recruiting!</h1>
						<p>Come and apply here!</p>
						<p><?php echo $this->Html->link('Show me how!',array('controller'=>'users','action'=>'login'), array('class' => 'btn btn-lg btn-primary', 'role' => 'button'))?></p>
					</div>
				</div>
			</div>
			<div class="item"> <img src="<?php echo $this->webroot; ?>img/slide2.png" alt="Second slide">
				<div class="container">
					<div class="carousel-caption">
						<h1>AsCII goes to the Accenture...</h1>
						<p>Accenture Philippines, in partnership with the Accenture Technology Academy (ATA) and the Massachusetts Institute of Technology (MIT) has launched Shaping the Future Forum 2014</p>
						<p><a class="btn btn-lg btn-primary" href="#" role="button">asdf</a></p>
					</div>
				</div>
			</div>
		</div>
		<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left"></span></a>
		<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right"></span></a>
	</div>
</div>
