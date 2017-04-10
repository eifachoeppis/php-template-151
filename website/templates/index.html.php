<!Doctype>
<html>
	<head>
		<title>Index</title>
	</head>
	<body>
		<h1>Hello</h1>
		<p>
			<?= (array_key_exists("email", $_SESSION)) ? $_SESSION["email"] : "" ?>
		</p>
	</body>
</html>