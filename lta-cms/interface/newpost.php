<?php
	session_start();
	if (!isset($_SESSION["isAdmin"])) {
		header("Location: http://" . $_SERVER["HTTP_HOST"] . "/lta/login");
		die();
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
		<link rel="stylesheet" type="text/css" href="/css/example.css">
		<link rel="shortcut icon" type='image/x-icon' href='/favicon.ico' />
		<link href='http://fonts.googleapis.com/css?family=Source+Code+Pro|Open+Sans:400,700|Roboto+Slab:400,700' rel='stylesheet' type='text/css'>
		<title>LTA Editor</title>

		<script src="/js/jquery-1.11.2.min.js"></script>
		<script src="/js/pagedown.js"></script>
	</head>
	<body>
		<div id="content" style="padding:40px; width:1000px;">	

			<div style="float:left; width:350px;">
				<h2>Create a New Post:</h2>
				
				<form action="/lta-cms/action-newpost" method="post" id="postForm">

					Title:<br>
					<input id="postTitle" type="text" style="width:100%;" name="postTitle"><br><br>

					Categories:<br>
					<i style="font-size:70%;">Separate categories with commas.<br>Use only numbers, letters, and spaces.</i><br>
					<input id="postCategories" type="text" style="width:100%;" name="postCategories"><br><br>

					Markdown Content:<br>
					<textarea type="text" form="postForm" id="postContent" name="postContent" style="width:100%; height:200px;">Type your *Markdown* text here!</textarea><br>

					<input type="submit">
				</form>
				<hr>
				<a href="login">Log out</a>
			</div>

			<div style="float:right; width:600px;">
				<h2>Live Preview:</h2>

				<div id="wysiwyg" class="article"></div>

				<i style="font-size:70%;">
					Previously used categories:<br>
					<?php
						require $_SERVER["DOCUMENT_ROOT"] . "/lta-cms/lta-back.php";
						$LTACMS = new LighterThanAir();
						$categories = array();
						foreach ($LTACMS->getPosts() as $x)
						{
								foreach ($LTACMS->getHeader(ROOT."/content/posts/{$x}")->CATEGORIES as $cat)
								array_push($categories, trim(strtolower($cat)));
						}
						$categories = array_unique($categories);
						sort($categories, SORT_STRING);

						foreach ($categories as $x)
							echo $x . ", ";
					?>
				</i>
			</div>

			<div style="clear:both; height:40px;">&nbsp;</div>
			
			<script type="text/javascript">
				var converter = new Markdown.Converter();
				var title = "Default Title";
				var content = "";

				//alert(html);
				$(document).ready(function(){
					content = $("#postContent").text();

					function updateWysiwyg(){
						$("#wysiwyg").html("<div class='stamp'>&nbsp;</div>" + converter.makeHtml("#" + title + "\n" + content));
					}

					//Update wysiwyg if title changes
					$("#postTitle").bind('input propertychange', function(){
						title = this.value;
						updateWysiwyg();
					});

					//Update wysiwyg if content changes
					$("#postContent").bind('input propertychange', function(){
						content = this.value;
						updateWysiwyg();
					});

					updateWysiwyg();
				});
			</script>
		</div>
	</body>
</html>