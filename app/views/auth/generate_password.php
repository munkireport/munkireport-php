<?$this->view('partials/head')?>

<div>
<form action="" method="post" accept-charset="UTF-8" >
    <h2><span>Generate password hash</span></h2>
<?php if(isset($generated_pwd)):?>
	<label for="genpwd">Add this line to config.php:</label><input type="text" id="genpwd" name="genpwd" class="text" value="$GLOBALS['auth_config']['<?php echo $login?>'] = '<?php echo $generated_pwd?>';"></input><br/>
	<input type="submit" id="submit" value="Start over" />
<?php else:?>
	<label for="loginusername">Username:</label><input type="text" id="loginusername" name="login" class="text" value="<?php echo $login?>"></input><br/>
	<label for="loginpassword">Password:</label><input type="password" id="loginpassword" name="password" class="text"></input>
	<input type="submit" id="submit" value="Generate" />
<?php endif?>
</form>
<form method="post" action="/login/">
<div style='display:none'><input type='hidden' name='csrfmiddlewaretoken' value='02d2a8353c8aa6461bcd921ac90417ee' /></div>
</form>


</div>

<?$this->view('partials/foot')?>