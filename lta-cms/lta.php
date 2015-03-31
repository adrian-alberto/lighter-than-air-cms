<?php
	/*

	TODO: Remove http://whitecollargames.whatever links and replace with something more dynamic

	{
		"TITLE": "Default",
		"DATE": 0,
		"UPDATED": 0,
		"ID": "tag:whitecollargames.com,1970-01-01:0:1",
		"CATEGORIES":[]
	}
	*/
	//$blog_meta = json_decode(file_get_contents(getcwd() . "/content/posts/meta.json"));
	if (file_exists(getcwd() . "/content/posts/meta.json"))
		$blog_meta = json_decode(file_get_contents(getcwd() . "/content/posts/meta.json"));
	else
		$blog_meta = json_decode(file_get_contents("http://whitecollargames.local/content/posts/meta.json"));
	$tag_url = "whitecollargames.com";
	class header
	{
		public $TITLE = "Default Title";
		public $DATE = 0;
		public $UPDATED = 0;
		public $ID;
		public $CATEGORIES = array();
	}

	class LighterThanAir
	{
		function generateHeader($timestamp = null, $title = "Default Title", $categories = null)
		{
			global $blog_meta, $tag_url;
			if (!$timestamp)
				$timestamp = time();
			$formattedDate = date("Y-m-d", $timestamp);

			$header = new header();
			$header->TITLE = $title;
			$header->DATE = $timestamp;
			$header->UPDATED = $timestamp;
			if ($categories !== null)
				$header->CATEGORIES = $categories;
			$header->ID = "tag:{$tag_url},{$formattedDate}:{$timestamp}:" . ($blog_meta->latest + 1);
			return json_encode($header);
		}

		function getHeaderData($file)
		{
			$content = file_get_contents($file);
			$matches = array();
			preg_match("#^<!--(.+)-->#", $content, $matches);
			return json_decode($matches[1]);
			//returns an object
		}

		function getContent($file)
		{
			$content = file_get_contents($file);
			$matches = array();
			preg_match("#(\n+.*)+#", $content, $matches);
			return $matches[0];
			//returns a string
		}

		function generateAtom($post_directory, $count = 20)
		{
			global $blog_meta, $Parsedown;
			$feed = '<?xml version="1.0" encoding="utf-8"?>

<feed xmlns="http://www.w3.org/2005/Atom">
	<title>Pushing Content</title>
	<subtitle>TODO: make subtitle</subtitle>
	<link href="http://whitecollargames.com/content/posts/atom.xml" rel="self"/>
	<link href="http://whitecollargames.com/"/>
	<id>tag:whitecollargames.com,2015:0</id>
	<updated>' . date('Y-m-dTh:m:s-08:00', time()) . '</updated>' . "\n";

			$i = $blog_meta->latest;
			while ($i > 0 && $count > 0)
			{
				$file = $post_directory . str_pad($i, 4, "0", STR_PAD_LEFT) . ".md";
				if (file_exists($file))
				{
					$headerData = self::getHeaderData($file);
					$postContent = strip_tags($Parsedown->text(self::getContent($file)));
					$feed = $feed . '<entry>
	<title>'. $headerData->TITLE .'</title>
	<link href="http://whitecollargames.com/blog?post='. $i .'"/>
	<id>'. $headerData->ID .'</id>
	<published>' . date('Y-m-dTh:m:s-08:00', $headerData->DATE) . '</published>
	<updated>' . date('Y-m-dTh:m:s-08:00', $headerData->UPDATED) . '</updated>
	<content>'. $postContent .'</content>
	<author>
		<name>Adrian Alberto</name>
		<uri>http://whitecollargames.com/</uri>
		<email>adrian@whitecollargames.com</email>
	</author>
</entry>' . "\n";
				}
				$i--;
				$count--;
			}



			return $feed . "</feed>";
		}
	}


?>
