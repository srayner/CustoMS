<?php if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..'); ?>

<?php

	if(isset($_SESSION['msg']['contact_us-error'])){
		echo '<div class="alert alert-danger" role="alert">'.$_SESSION['msg']['contact_us-error'].'</div>';
		unset($_SESSION['msg']['contact_us-error']);
	}

	if(isset($_SESSION['msg']['contact_us-success'])){
		echo '<div class="alert alert-success" role="alert">'.$_SESSION['msg']['contact_us-success'].'</div>';
		unset($_SESSION['msg']['contact_us-success']);
	}

?>

<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" id="contact">
	<fieldset>
		<legend>Contact Us</legend>
		<div class="form-group">
			<label for="name">Name:</label>
			<input class="form-control" type="text" id="name" name="name" placeholder="Your name" value="<?php echo (isset($_SESSION['username']) ? $_SESSION['username'] : '' ); ?>" required />
		</div>
		<br />
		<div class="form-group">
			<label for="email">Email:</label>
			<input class="form-control" type="email" id="email" name="email" placeholder="Your email address"  value="<?php echo (isset($_SESSION['email']) ? $_SESSION['email'] : '' ); ?>" required />
		</div>
		<br />
		<div class="form-group">
			<label for="phone">Phone Number:</label>
			<input class="form-control" type="tel" id="phone" name="phone" placeholder="Your phone number" />
		</div>
		<br />
		<div class="form-group">
			<label for="subject">Subject:</label> &nbsp;
			<select name="subject" id="subject">
			  	<option value="General&nbsp;Enquiry">General Enquiry</option>
				<option value="Accounts">Accounts</option>
			  	<option value="Tech&nbsp;Support">Technical Support</option>
			  	<option value="Complaints">Complaints</option>
			</select>
		</div>
		<br />
		<div class="form-group">
			<label for="message">Message:</label>
			<textarea class="form-control" id="message" rows="7" name="message" placeholder="Your message"></textarea>
		</div>
		<br />
		<input class="btn btn-primary btn-block" value="Send Message" type="submit" name="submit" />
	</fieldset>
</form>