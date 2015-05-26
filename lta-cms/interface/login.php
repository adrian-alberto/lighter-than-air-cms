<?php
	session_start();
	session_unset();
	if (@$_POST["username"] !== null && @$_POST["password"] !== null)
	{
		$username = $_POST["username"];
		$password = $_POST["password"];

		# THIS IS OBVIOUSLY TEMPORARY!@!$#!#AVFGDadfasdf
		if ($username === "admin" && $password === "password")
		{
			$_SESSION["isAdmin"] = true;
			#change this to the panel instead of the new post interface
			echo $_SERVER["HTTP_HOST"];
			header("Location: http://" . $_SERVER["HTTP_HOST"] . "/lta/newpost");
			die();
		}
		else
		{
			echo "Invalid username/password.<br><br>";
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
		<link href='http://fonts.googleapis.com/css?family=Source+Code+Pro|Open+Sans:400,700|Roboto+Slab:400,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="/css/example.css">
		<title>LTA Login</title>
	</head>
	<body id="content" style="padding-top:60px; width:400px">
		<?php
			require $_SERVER["DOCUMENT_ROOT"] . "/lta-cms/lta-login-form.php";
		?>
	</body>
</html>