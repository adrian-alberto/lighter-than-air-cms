<?php
define("ROOT", $_SERVER["DOCUMENT_ROOT"]);
require ROOT."/lta-cms/parsedown.php";
$ParseDown = new ParseDown();

 //TODO: Pull these from $blog_meta
$tag_url = "whitecollargames.com";
$timezone = -8;

if (file_exists(ROOT."/content/blog-meta.json"))
	$blog_meta = json_decode(file_get_contents(ROOT."/content/blog-meta.json"));
else
	trigger_error("Missing LTA metadata.");

//This gets used a lot, globalizing is a good idea.
$posts = array();
foreach (preg_grep("#[0-9]+.*\.md#", scandir(ROOT."/content/posts")) as $filename)
	array_push($posts, $filename);
natsort($posts);

#####################################################################################
class Header
{
	public $TITLE;
	public $DATE;
	public $UPDATED;
	public $ID = 0;
	public $CATEGORIES;
}
#####################################################################################
class LighterThanAir
{
	function getPasswordHash()
	{
		$passFile = dirname($_SERVER["DOCUMENT_ROOT"]) . "/ltapass";
		if (!file_exists($passFile))
		{
			file_put_contents($passFile, password_hash("password", PASSWORD_DEFAULT));
		}
		return file_get_contents($passFile);
	}

	function setPassword($pass)
	{
		$passFile = dirname($_SERVER["DOCUMENT_ROOT"]) . "/ltapass";
		file_put_contents($passFile, password_hash($pass, PASSWORD_DEFAULT));
	}


	//Returns array of filenames from latest
	//TODO: skip hidden files
	function getPosts($count = null, $start = null)
	{
		global $posts;
		$output = array();
		
		$n = 0;
		for ($i = count($posts) - 1; $i >= 0; $i--)
		{
			//Get id
			$matches = array();
			preg_match("#([0-9]+)#", $posts[$i], $matches);
			if ($start === null | (int) $matches[1] <= $start)
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

	//Returns latest id
	function getLatestPost()
	{
		$found = $this->getPosts(1);
		if (count($found) === 1)
		{
			//pull id out of filename
			$matches = array();
			preg_match("<([0-9]+).*\.md>", $found[0], $matches);
			return (int) $matches[1];
		}
		else
			return;
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

	function generateNextHeader($post_title, $post_categories, $post_id)
	{
		$time = time();
		$header = new Header();
		$header->TITLE = $post_title;
		$header->CATEGORIES = $post_categories;
		$header->ID = $post_id; //TODO: full id for atom usage
		$header->DATE = $time;
		$header->UPDATED = $time;
		return "<!--" . json_encode($header) . "-->";
	}

	function outputPost($post_id, $file = null)
	{
		global $ParseDown, $timezone;
		if ($file === null)
		{
			$found = $this->getPosts(1, $post_id);
			if (count($found) === 1)
				$file = ROOT."/content/posts/" . $found[0];
			else
				return;
		}

		if (file_exists($file))
		{
			$header = $this->getHeader($file);

			$date = date("F d, Y", $header->DATE + 3600 * $timezone);
			$output = "<div id='post_{$post_id}' class='article'>\n<a href='#post_{$post_id}' class='stamp'>{$date}</a>";
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
			$this->outputPost($matches[1], ROOT."/content/posts/{$f}");
		}
	}

}
?>
