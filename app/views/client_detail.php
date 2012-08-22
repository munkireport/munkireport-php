<?$this->view('partials/head')?>

<?$machine = new Machine($serial);?>

<h1><img style="vertical-align:middle;" src="<?printf('https://km.support.apple.com.edgekey.net/kb/securedImage.jsp?configcode=%s&size=72x72', substr($serial, -4))?>" alt=""><?=$machine->computer_name?></h1>

<div id="tabs">
	<ul class="clearfix">
		<li><a href="#machine">Machine info</a></li>
		<li><a href="#munki">Munki</a></li>
		<li><a href="#warranty">Warranty</a></li>
		<li><a href="#apple-software">Apple Software</a></li>
		<li><a href="#third-party-software">Third Party Software</a></li>
	</ul>
	
	<div id='machine'>
		<?$report_type = (object) array('name'=>'Munkireport', 'desc' => 'munkireport')?>
		<?$this->view('partials/machine_info', array('report_type' => $report_type))?>
	</div>
	
	<div id='munki'>
		<h2>Munki</h2>
		<?$this->view('partials/munki')?>
	</div>
	
	<div id='warranty'>
		<h2>Warranty</h2>
		<?$this->view('partials/warranty')?>
	</div>
	
	<div id='apple-software'>
		<h2>Installed Apple Software</h2>
		<?$this->view('partials/install_history', array('apple'=> TRUE))?>
	</div>
	
	<div id='third-party-software'>
		<h2>Installed Third-Party Software</h2>
		<?$this->view('partials/install_history', array('apple'=> FALSE))?>
	</div>

</div>

<script type="text/javascript">

$(document).ready(function(){
	$('#tabs div').hide();
	$('#tabs div:first').show();
	$('#tabs ul li:first').addClass('active');
	$('#tabs ul li a').click(function(e){ 
		$('#tabs ul li').removeClass('active');
		$(this).parent().addClass('active'); 
		var currentTab = $(this).attr('href');
		window.location.hash = currentTab;
		$('#tabs div').hide();
		$(currentTab).show();
		e.preventDefault();
	});
	if(window.location.hash)
	{
		currentTab = window.location.hash;
		$('#tabs div').hide();
		$(currentTab).show();
		$('#tabs ul li').removeClass('active');
		$('#tabs a[href="'+currentTab+'"]').parent().addClass('active');
	}
});
</script>


<?$this->view('partials/foot')?>
