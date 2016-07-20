<?php if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..'); ?>

<?php

	if(isset($_SESSION['msg']['sign_up-error'])){
		echo '<div class="alert alert-danger" role="alert">' . $_SESSION['msg']['sign_up-error'] . '</div>';
		unset($_SESSION['msg']['sign_up-error']);
	}			
	if(isset($_SESSION['msg']['sign_up-success'])){
		echo '<div class="alert alert-success" role="alert">' . $_SESSION['msg']['sign_up-success'] . '</div>';
		unset($_SESSION['msg']['sign_up-success']);
	}
	
?>

<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
	<fieldset>
		<legend>Sign up for an account</legend>
		<div class="form-group">
			<label for="username">Username:</label>
			<input class="form-control" type="text" name="username" id="username" placeholder="username" required/>
		</div>
		<div class="form-group">
			<label for="email">Email Address:</label>
			<input class="form-control" type="email" name="email" id="email" placeholder="email" required/>
		</div>
		<div class="form-group">
			<label for="password">Password:</label>
			<button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#passwordGeneratorModal">Generate Password</button>
			<input class="form-control" type="password" name="password" id="password" placeholder="password" required/>
		</div>
		<div class="form-group">
			<label for="password_repeat">Repeat Password:</label>
			<input class="form-control" type="password" id="password_repeat" name="password_repeat" placeholder="password" required/>
		</div>
		<em>Which role would you like?</em>
		<br />
		<input type="radio" name="role" id="journalist" value="journalist" checked> <label for="journalist">Journalist</label> &nbsp; &nbsp; <input type="radio" name="role" id="editor" value="editor"> <label for="editor">Editor</label>
		<br />
		<br />
		<input class="btn btn-primary btn-block" type="submit" name="submit" value="Sign up" />
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