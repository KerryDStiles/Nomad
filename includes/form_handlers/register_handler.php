<?php

//Declaring variables to preven errors
$fname = ""; //First Name
$lname = ""; //Last Name
$em = ""; //Email
$em2 = ""; //Email 2
$password = ""; //Password
$password2 = ""; //Password 2
$date = ""; //Sign up date
$error_array = array(); //Holds error messages

if(isset($_POST['register_button'])){

	//Registration form values

	//First name
	$fname = strip_tags($_POST['reg_fname']); //Removes HTML tags
	$fname = str_replace(' ', '', $fname); //Remove spaces
	$fname = ucfirst(strtolower($fname)); //Uppercase letter first
	$_SESSION['reg_fname'] = $fname; //Stores first name into session variable

	//Last name
	$lname = strip_tags($_POST['reg_lname']); //Removes HTML tags
	$lname = str_replace(' ', '', $lname); //Remove spaces
	$lname = ucfirst(strtolower($lname)); //Uppercase letter first
	$_SESSION['reg_lname'] = $lname; //Stores last name into session variable

	//Email 
	$em = strip_tags($_POST['reg_email']); //Removes HTML tags
	$em = str_replace(' ', '', $em); //Remove spaces
	$em = ucfirst(strtolower($em)); //Uppercase letter first
	$_SESSION['reg_email'] = $em; //Stores email into session variable

	//Email 2
	$em2 = strip_tags($_POST['reg_email2']); //Removes HTML tags
	$em2 = str_replace(' ', '', $em2); //Remove spaces
	$em2 = ucfirst(strtolower($em2)); //Uppercase letter first
	$_SESSION['reg_email2'] = $em2; //Stores email2 into session variable


	//Password
	$password = strip_tags($_POST['reg_password']); //Removes HTML tags
	$password2 = strip_tags($_POST['reg_password2']); //Removes HTML tags

	$date = date("Y-m-d"); //Current date

	//Validate emails match
	if ($em == $em2) {
		//Check for valid email format
		if (filter_var($em, FILTER_VALIDATE_EMAIL)) {
			
			$em = filter_var($em, FILTER_VALIDATE_EMAIL);

			//Check if email already exists
			$e_check = mysqli_query($con, "SELECT email FROM users WHERE email = '$em'");

			//Count the number of rows returned
			$num_rows = mysqli_num_rows($e_check);

			if($num_rows > 0) {
				array_push($error_array, "Email already in use.<br>");
			}
		}

		else {
			array_push($error_array, "Invalid Format!<br>");
		}
			
	}

	else {
		array_push($error_array, "Emails don't match!<br>");
	}
	//Validate name length
	if (strlen($fname) > 25 || strlen($fname) < 2) {
		array_push($error_array, "Your first name must be between 2 and 25 characters.<br>");
	}
	if (strlen($lname) > 25 || strlen($lname) < 2) {
		array_push($error_array, "Your last name must be between 2 and 25 characters.<br>");
	}

	//Validate passwords match
	if ($password != $password2) {
		array_push($error_array, "Your passwords do not match!<br>");
	}

	//Verify password only contains english characters
	else {
		if (preg_match('/[^A-Za-z0-9]/', $password)) {
			array_push($error_array, "Your password can only contain english characters.<br>");
		}
	}

	//Verify that password is between 2 and 30 characters
	if (strlen($password > 30 || strlen($password) < 5)) {
		array_push($error_array, "Your password must be between 5 and 30 characters.<br>");
	}

	if (empty($error_array)) {
		$password = md5($password); //Encrypt password before sending to database

		//Generate username by concatenating first and last name
		$username = strtolower($fname . "_" . $lname);
		$check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username = '$username'");

		$i = 0;
		//If username exists add number to username
		while (mysqli_num_rows($check_username_query) != 0) {
			$i++; //Add 1 to i
			$username = $username . "_" . $i;
			$check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username = '$username'");
		}

		//Profile picture assignment
		$rand = rand(1, 2); //Random number between 1 and 2

		if($rand == 1) 
			$profile_pic = "assets/images/profile_pics/defaults/head_carrot.png";
	
		else if($rand == 2) 
			$profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png";

		$query = mysqli_query($con, "INSERT INTO users VALUES ('', '$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', '0', '0', 'no', ',')");
		array_push($error_array, "<span style = 'color: #14c800;'>Registration Successful! Go ahead and Log In!</span><br>");

		//Clear session variables
		$_SESSION['reg_fname'] = "";
		$_SESSION['reg_lname'] = "";
		$_SESSION['reg_email'] = "";
		$_SESSION['reg_email2'] = "";
	}
}
?>