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
				<?php
					require "lta-cms/lta-back.php";
					$LTACMS = new LighterThanAir();


					@$post_id = htmlspecialchars($_GET["id"]);
					if ($post_id !== null && preg_match("<^\d+$>", $post_id) === 1)
						$LTACMS->outputPost($post_id);
					else
						$LTACMS->outputMultiplePosts();
					
				?>
			</div>
		</div>
	</body
</html>