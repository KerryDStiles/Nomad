$(document).ready(function() {

	//On click signup, hide login and show registration
	$("#signup").click(function() {
		$("#signinform").slideUp("slow", function() {
			$("#signupform").slideDown("slow");
		});
	});

	//On click signin, hide register and show login
	$("#signin").click(function() {
		$("#signupform").slideUp("slow", function() {
			$("#signinform").slideDown("slow");
		});
	});


});