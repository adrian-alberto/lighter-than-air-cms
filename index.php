<!DOCTYPE html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
		<link href='http://fonts.googleapis.com/css?family=Source+Code+Pro|Open+Sans:400,700|Roboto+Slab:400,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="css/example.css">
		<title>Example LTA Blog</title>
	</head>

	<body>
		<div id="content">
			<div id="posts">
				Hello, world!

				<?php
					require "lta-cms/lta-back.php";
					$LTACMS = new LighterThanAir();
					$LTACMS->outputMultiplePosts();
				?>
			</div>
		</div>
	</body
</html>