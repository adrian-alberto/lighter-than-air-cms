<?php
/*
//Blog post metadata format
{
	"TITLE": "Default",
	"DATE": 0,
	"UPDATED": 0,
	"ID": "tag:whitecollargames.com,1970-01-01:0:1",
	"CATEGORIES":[]
}

//CMS Architecture
Root
	content
		posts
			0001-default-title.md
			0002-second-post.md
		images
			test.png
		blog-meta.json
		atom.xml
	lta-cms
		lta-front.js
		lta-back.php
		interface.php
	css
		example.css
	index.php
	.htaccess
*/

require "lta-cms/parsedown.php";
$ParseDown = new ParseDown();


$blog_meta;
if (file_exists("content/blog-meta.json"))
	$blog_meta = json_decode(file_get_contents("content/blog-meta.json"));
else
	trigger_error("Missing LTA metadata.");

 //TODO: Pull these from $blog_meta
$tag_url = "whitecollargames.com";
$timezone = -8;



class Header
{
	public $TITLE;
	public $DATE;
	public $UPDATED;
	public $ID = 0;
	public $CATEGORIES;

	function __construct($file) //
	{

	}
}

class LighterThanAir
{
	//Returns array of filenames from latest
	//TODO: skip hidden files
	function getPosts($count = null, $start = null)
	{
		$posts = array();
		$output = array();

		foreach (preg_grep("#[0-9]+.*\.md#", scandir("content/posts")) as $filename)
			array_push($posts, $filename);

		natsort($posts);
		
		$n = 0;
		for ($i = count($posts) - 1; $i >= 0; $i--)
		{
			//Get id
			$matches = array();
			preg_match("#([0-9]+)#", $posts[$i], $matches);
			if ($start === null | $matches[1] <= $start)
			{
				//Count added posts, add post to output
				$n++;
				if ($count !== null & $n > $count)
					break;
				else
					array_push($output, $posts[$i]);
			}
		}

		return $output;
	}

	function getMarkdownContent($file)
	{
		$matches = array();
		preg_match("#(\n+.*)+#", file_get_contents($file), $matches);
		return $matches[0];
	}

	function getHeader($file)
	{
		$content = file_get_contents($file);
		$matches = array();
		preg_match("#^<!--(.+)-->#", $content, $matches);
		return json_decode($matches[1]);
	}

	function outputPost($post_id, $file = null)
	{
		global $ParseDown, $timezone;
		if ($file === null)
		{
			$found = $this->getPosts(1, $post_id);
			if (count($found) === 1)
				$file = "content/posts/" . $found[0];
			else
				return;
		}

		if (file_exists($file))
		{
			$header = $this->getHeader($file);

			$date = date("F d, Y", $header->DATE + 3600 * $timezone);
			$output = "<div id='post_{$post_id}' class='article'>\n<a href='#' class='stamp'>{$date}</a>";
			$output .= $ParseDown->text($this->getMarkdownContent($file));
			echo $output . "</div>";
		}
	}

	function outputMultiplePosts($count = null, $start = null)
	{
		$filenames = $this->getPosts($count, $start);
		foreach($filenames as $f)
		{
			$matches = array();
			preg_match("<([0-9]+).*\.md>", $f, $matches);
			$this->outputPost($matches[1], "content/posts/{$f}");
		}
	}

}

?>
