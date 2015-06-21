<div class="row">
  	<div class="col-lg-6 col-lg-offset-1">
  	  	<h2>Signup here!</h2>
		 	<?php echo $this->Session->flash(); ?>
  			<?php echo $this->Form->create('User', array('class'=>'form-horizontal','inputDefaults'=>array('label'=>false)));?>

  	 	  	<div class="form-group">
		    	<label class="col-sm-2 control-label">Username</label>
			    <div class="col-sm-10">
			      <?php echo $this->Form->input('username', array('class'=>'form-control'));?>
			    </div>
		  	</div>

			<div class="form-group">
			    <label class="col-sm-2 control-label">Password</label>
			    <div class="col-sm-10">
			       <?php echo $this->Form->input('password', array('type' => 'password', 'class'=>'form-control'));?>
			    </div>
			</div>

			<div class="form-group">
			    <label class="col-sm-2 control-label">Email</label>
			    <div class="col-sm-10">
			       <?php echo $this->Form->input('email', array('type' => 'email', 'class'=>'form-control'));?>
			    </div>
			</div>
			 
			<div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			      <?php echo $this->Form->submit('Signup!',array('class'=>'btn btn-primary'))?>
			    </div>
			</div>
		<?php echo $this->Form->end();?>
    </div>
</div>
