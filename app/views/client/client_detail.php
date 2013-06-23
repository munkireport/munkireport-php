<?$this->view('partials/head')?>

<div class="container">
	<div class="row">
		<div class="span12">
			<h1>
				<img style="vertical-align:middle; width:75px" src="<?=$meta['iconURL']?>" alt="">
				<?=$machine['computer_name']?>
				<?if(Config::get('vnc_link')):?>
				<a class="btn" href="<?printf(Config::get('vnc_link'), $meta['remote_ip'])?>">VNC</a>
				<?endif?>
			</h1>

			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#machine" data-toggle="tab">Machine info</a>
				</li>
				<li><a href="#munki" data-toggle="tab">Munki</a></li>
				<li><a href="#warranty" data-toggle="tab">Warranty</a></li>
				<li>
					<a href="#apple-software" data-toggle="tab">Apple Software</a>
				</li>
				<li>
					<a href="#third-party-software" data-toggle="tab">Third Party Software</a>
				</li>
			</ul>

			<div class="tab-content">
				<div class="tab-pane active" id='machine'>
					<?$this->view('partials/machine_info')?>
				</div>
	
				<div class="tab-pane" id='munki'>
					<h2>Munki</h2>
					<?$this->view('partials/munki')?>
				</div>
	
				<div class="tab-pane" id='warranty'>
					<h2>Warranty</h2>
					<?$this->view('partials/warranty')?>
				</div>
	
				<div class="tab-pane" id='apple-software'>
					<h2>Installed Apple Software</h2>
					<?$this->view('partials/install_history', array('apple'=> TRUE))?>
				</div>
	
				<div class="tab-pane" id='third-party-software'>
					<h2>Installed Third-Party Software</h2>
					<?$this->view('partials/install_history', array('apple'=> FALSE))?>
				</div>
			</div>
	    </div> <!-- /span 12 -->
	</div> <!-- /row -->
</div>  <!-- /container -->

<?$this->view('partials/foot')?>