<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
		<link href='http://fonts.googleapis.com/css?family=Source+Code+Pro|Open+Sans:400,700|Roboto+Slab:400,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="/css/example.css">
		<title>Example LTA Blog</title>
	</head>

	<body>
		<a style="float:right; padding:10px;" href="/lta/login">log in</a>
		<div id="content">
			<div id="posts">
				
				<div style="text-align:center; padding-bottom:30px">
					<img src="/css/img/testlogo.png" alt="???" style="display:block; width: 160px; margin-left:auto; margin-right:auto;">
					<h2 style="margin-bottom:0; margin-top:0">Your Pretentious Dev Blog</h2>
					<p style="margin-top:0; margin-bottom:10px">What even is that? Is that a turtle? Gecko?</p>
					<hr style="position:absolute; left:0; width:100%">
				</div>

				<?php
					require "/lta-cms/lta-back.php";
					$LTACMS = new LighterThanAir();

					@$post_id = htmlspecialchars($_GET["id"]);
					if ($post_id !== null && preg_match("<^\d+$>", $post_id) === 1)
						$LTACMS->outputPost($post_id);
					else
						$LTACMS->outputMultiplePosts();
					
				?>
			</div>
		</div>
	</body>
</html>