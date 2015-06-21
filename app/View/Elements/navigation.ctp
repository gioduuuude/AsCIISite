<div class="navbar-wrapper">
    <div class="container">
		<div class = "navbar navbar-default" role = "navigation">
			<div class = "container-fluid">
				<div class="navbar-header">
					<button type = "button" class = "navbar-toggle" data-toggle = "collapse" data-target= ".navbar-collapse">
						<span class="sr-only">Toggle navigation</span> 
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" rel="home">
						<img class = "navbar-brand" id = "navlogo" src="<?php echo $this->webroot; ?>img/logo.png"> Association for Computer Intelligence Integration
					</a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
						<?php if($this->Session->check('Auth.User')):?>
						<li class="dropdown">
							<a href="" class="dropdown-toggle" data-toggle="dropdown">
								<?php echo $this->Session->read('Auth.User.username');?>
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu" role="menu">
								<li><?php echo $this->Html->link(__('Profile'),array('controller'=>'users','action'=>'profile'))?></li>
								<li><?php echo $this->Html->link(__('Logout'),array('controller'=>'users','action'=>'logout'))?></li>
							</ul>
						</li>
						<?php endif;?>
						<li><?php echo $this->Html->link(__('Home'),'/')?></li>
						<li class="dropdown"> 
							<a href="" class="dropdown-toggle" data-toggle="dropdown">Members
								<span class="caret"></span>
							</a>
							<?php if(!$this->Session->check('Auth.User')):?>
							<ul class="dropdown-menu" role="menu">
								
									<li><?php echo $this->Html->link(__('Login'),array('controller'=>'users','action'=>'login'))?></li>
									<li><?php echo $this->Html->link(__('Signup'),array('controller'=>'users','action'=>'signup'))?></li>
									<!--<li><a href="#login" data-toggle="modal">Login</a></li> -->
								
								<!--<li><a href="#">Member Listing</a></li>
								<li><a href="#">Officers</a></li>
								<li class="divider"></li>
								<li class="dropdown-header">Branches of AsCII</li>
								<li><a href="#">Administrative Branch</a></li>
								<li><a href="#">Academic Branch</a></li>
								<li><a href="#">Special Interest Groups</a></li> -->
							</ul>
							<?php endif;?>
						</li>
						<li><?php echo $this->Html->link(__('Forum'), array('controller'=>'forum', 'action'=>'index'))?></li>
						<li><?php echo $this->Html->link(__('About Us'),array('controller'=>'site','action'=>'aboutus'))?></li>
						<li><?php echo $this->Html->link(__('Contact Us'),array('controller'=>'site','action'=>'contactus'))?></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Login Dialogbox <?php Debugger::dump ($post);Debugger::dump ($userFields); ?>-->
<div class = "modal fade" id = "login" role = "dialog">
    <div class = "modal-dialog">
        <div class = "modal-content">
            <div class = "modal-header">
                <h2 class="form-signin-heading">Please sign in</h2>
            </div>
            <div class = "modal-body">
                <?php echo $this->Session->flash(); ?>
                <?php echo $this->Form->create('User',array('class'=>'form-horizontal','inputDefaults'=>array('label'=>false)));?>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10">
                        <?php echo $this->Form->input('username',array('class'=>'form-control', 'placeholder'=>'Username'));?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                        <?php echo $this->Form->input('password',array('class'=>'form-control', 'placeholder'=>'Password'));?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <?php echo $this->Form->button('Login',array('class'=>'btn btn-lg btn-primary btn-block'))?>
                    </div>
                </div>

                <?php echo $this->Form->end();?>
            </div>
        </div>
    </div>
</div>
