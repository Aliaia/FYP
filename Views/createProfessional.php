<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Condition Tracker</title>

    <!-- CSS -->
    <link rel="stylesheet" href="css/Style.css"> <!-- custom styles -->

    <script type="text/javascript">

    	// Checks to see if the inputed data is valid to be submitted
    	function validateForm() {
    		var firstName = document.forms["createProfessional"]["firstName"].value;
    		var middleName = document.forms["createProfessional"]["middleName"].value;
    		var lastName = document.forms["createProfessional"]["lastName"].value;
    		var email = document.forms["createProfessional"]["email"].value;
    		var email2 = document.forms["createProfessional"]["email2"].value
    		var password = document.forms["createProfessional"]["password"].value;
    		var password2 = document.forms["createProfessional"]["password2"].value
    		
    		var passwordRE = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/
    		var emailRE = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    		if (firstName == "" ) {
    			document.getElementsByClassName("error")[0].innerHTML = "A first name is required";
    			return false;
    		} else if (lastName == "" ) {
    			document.getElementsByClassName("error")[0].innerHTML = "A last name is required";
    			return false;
    		} else if (email == "") {
    			document.getElementsByClassName("error")[0].innerHTML = "An email is required";
    			return false;
    		} else if (password == "") {
    			document.getElementsByClassName("error")[0].innerHTML = "A password is required";
    			return false;
    		} else if (email != email2) {
    			document.getElementsByClassName("error")[0].innerHTML = "Emails do not match.";
    			return false;
    		} else if (passwordRE.test(String(password)) == false){
                document.getElementsByClassName("error")[0].innerHTML = "password must have 8 characters, at least 1 uppercase, and one number.";
                return false;
            } else if (password != password2) {
    			document.getElementsByClassName("error")[0].innerHTML = "Passwords do not match";
    			return false;
    		} else if (emailRE.test(String(email).toLowerCase()) == false || passwordRE.test(String(password)) == false) {
    			document.getElementsByClassName("error")[0].innerHTML = "Please enter a valid email address";
    			return false;
            }  else {
    			return true;
    			document.getElementsByClassName("error")[0].innerHTML = "</br>";
    		}
    	}

    	<?php session_start() ?>

    	function onLoad() {

    		<?php 

    			if (isset($_SESSION['email_exists']) && $_SESSION['email_exists'] == true) {
    		?> 
    				document.getElementsByClassName("error")[0].innerHTML = "Email is already registered to an account."; 
    		<?php
    				unset($_SESSION['email_exists']);
    			
    			} 
    		?>
    	}

    </script>

</head>
<body onload="onLoad()">
	<div class="container">

		<h1>Condition Tracker</h1>
	      <h2>An easier way to track health conditions</h2>

	    <nav class="navigation">
		  	
		  	<ul class="Nav-bar">
		   		<li><a  href="AboutUs.html">About us</a></li>
		   		<li><a href="ContactUs.html">Contact us</a></li>
		   		<li><a href="UserLogin.php">User Login</a></li>
		   		<li><a href="ProfessionalLogin.php">Clinician Login</a></li>
		   	</ul>
		</nav>
	</div>
<div class="content">
	
	<p> Please enter your details, fields marked with a * are required </p>
	<p class="error"> </br> </p> 

	<div class="titles">
		First Name: *	</br>
		Middle Name: </br>
		Last Name: * </br>
		NHS Email Address: * </br>
		Confirm Email: * </br>
		Password: *</br>
		Confirm Password: *</br>

	</div>

	<form class="inputForm" name="createProfessional" method="post" action="../resources/createProfessional.php" onsubmit="return validateForm()">
		<input class="input" type="text" title="First Name" name="firstName"> 
		<input class="input" type="text" title="Middle Name" name="middleName">
		<input class="input" type="text" title="Last Name" name="lastName"> 
		<input class="input" type="text" title="Email" name="email">
		<input class="input" type="text" title="Confirm Email" name="email2">
		<input class="input" type="password" title="Password" name="password">
		<input class="input" type="password" title="Confirm Password" name="password2">
		<input class="input" type="submit" name="CreateProfessional" value="Create">
	</form>
</div>
</body>