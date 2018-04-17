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
    		var firstName = document.forms["createUser"]["firstName"].value;
    		var middleName = document.forms["createUser"]["middleName"].value;
    		var lastName = document.forms["createUser"]["lastName"].value;
    		var gender = document.forms["createUser"]["gender"].value;
    		var birthday = document.forms["createUser"]["birthday"].value;
    		var email = document.forms["createUser"]["email"].value;
    		var email2 = document.forms["createUser"]["email2"].value
    		var password = document.forms["createUser"]["password"].value;
    		var password2 = document.forms["createUser"]["password2"].value
    		
    		var passwordRE = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/
    		var emailRE = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    		if (firstName == "" ) {
    			document.getElementsByClassName("error")[0].innerHTML = "A first name is required";
    			return false;
    		} else if (lastName == "" ) {
    			document.getElementsByClassName("error")[0].innerHTML = "A last name is required";
    			return false;
    		} else if (gender == "" ) {
    			document.getElementsByClassName("error")[0].innerHTML = "Gender is required";
    			return false;
    		} else if (birthday == "") {
    			document.getElementsByClassName("error")[0].innerHTML = "A birthdate is required";
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
    		} else if (password != password2) {
    			document.getElementsByClassName("error")[0].innerHTML = "Passwords do not match";
    			return false;
    		} else if (emailRE.test(String(email).toLowerCase()) == false || passwordRE.test(String(password)) == false) {
    			document.getElementsByClassName("error")[0].innerHTML = "Please enter a valid email address and password";
    			return false;
    		} else {
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
		   		<li><a href="ProfessionalLogin.html">Clinician Login</a></li>
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
		Birthday: * </br>
		Gender: * </br>
		Email Address: * </br>
		Confirm Email: * </br>
		Password: *</br>
		Confirm Password: *</br>

	</div>

	<form class="inputForm" name="createUser" method="post" action="../resources/createUser.php" onsubmit="return validateForm()">
		<input class="input" type="text" title="First Name" name="firstName"> 
		<input class="input" type="text" title="Middle Name" name="middleName">
		<input class="input" type="text" title="Last Name" name="lastName"> 
		<input class="input" type="date" title="Birthday" name="birthday"> 
		<label class="radio" for="Female">Female</label>
		<label class="radio" for="Male">Male</label>
		<label class="radio" for="Other">Other</label>
		</br>
		<input class="radio" type="radio" title="Female" name="gender" id="Female" value="Female">
		<input class="radio" type="radio" title="Male" name="gender" id="Male" value="Male">
		<input class="radio" type="radio" title="Other" name="gender" id="Other" value="Other">
		<input class="input" type="text" title="Email" name="email">
		<input class="input" type="text" title="Confirm Email" name="email2">
		<input class="input" type="password" title="Password" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" name="password">
		<input class="input" type="password" title="Confirm Password" name="password2">
		<input class="input" type="submit" name="CreateUser" value="Create">
	</form>
</div>
</body>