<!DOCTYPE html>
<html>
<head>
    <?php echo $this->Html->charset(); ?>
    <title>Association for Computer Intelligence Integration</title>
    <meta name = "viewport" content = "width = device-width, initial-scale = 1.0">

    <?php
        echo $this->Html->meta('icon');
        echo $this->Html->css(array('navbar', 'signin', 'decoda', 'style', 'index'));
        echo $this->Html->css('bootstrap.min.css');
 
        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
    ?>
</head>
<body>
    <div class = "container-fluid" style = "padding-top: 2.4%;">
		<?php echo $this->element('navigation');?>
	   
		<div id = "divContent" class="container">
			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>

			<hr>
			<footer>
				<p class="pull-right"><a href="#">Back to top</a></p>
				<p>&copy; 2014 Association for Computer Intelligence Integration &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
			</footer>
		   
		</div> <!-- container -->
	</div><!-- container-fluid -->
     
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!--<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script> -->
    <script type = "text/javascript" src = "<?php echo $this->webroot; ?>jQuery/jquery-1.11.1.js"></script>
    <?php echo $this->Html->script('bootstrap.min'); ?>
</body>
</html>
