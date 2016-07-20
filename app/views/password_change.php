<?php if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..'); ?>

<?php
        
	if(isset($_SESSION['msg']['password_change-error'])){
		echo '<div class="alert alert-danger" role="alert">' . $_SESSION['msg']['password_change-error'] . '</div>';
		unset($_SESSION['msg']['password_change-error']);
	}
	
	if(isset($_SESSION['msg']['password_change-success'])){
		echo '<div class="alert alert-success" role="alert">' . $_SESSION['msg']['password_change-success'] . '</div>';
		unset($_SESSION['msg']['password_change-success']);
	}

?>
			
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
	<fieldset>
		<legend>Change your password</legend>
		<div class="form-group">
			<label for="email">Email:</label>
			<input class="form-control" id="email" type="email" name="email" placeholder="Email" required/>
		</div>
		<div class="form-group">
			<label for="password">Password:</label>
			<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#passwordGeneratorModal">Generate Password</button>
			<input class="form-control" id="password" type="password" name="password" placeholder="Password" required/>
		</div>
		<div class="form-group">
			<label for="password_repeat">Password:</label>
			<input class="form-control" id="password_repeat" type="password" name="password_repeat" placeholder="Password" required/>
		</div>
		<input class="btn btn-primary btn-block" type="submit" name="submit" value="Change Password" />
	</fieldset>
</form>

<div class="modal fade" id="passwordGeneratorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Password Generator</h4>
      </div>
      <div class="modal-body">
      	<div>
      		Password Strength: <span id="password_length">8</span>/18
      		<br />
      		<input id="password_strength" type="range" min="8" max="18" step="1" value="8" />
      	</div>
		<button class="btn btn-primary" id="generate_button">Generate</button>
		<div id="password_generated"></div>
		<div>Please select a length from the slider above and click the generate button to generate a unique password. Make sure you save the generated password before clicking the use password button.</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button id="use_password_button" type="button" class="btn btn-primary" data-dismiss="modal">Use Password</button>
      </div>
    </div>
  </div>
</div>