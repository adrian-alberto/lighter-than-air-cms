<?php
    require "atomgen.php";
    require "parsedown.php";
    $AtomGen = new AtomGen();
    $Parsedown = new Parsedown();
    $post_directory = dirname(dirname(__FILE__)) . "/content/posts/";
 
    if (@$_POST["postTitle"] && @$_POST["postContent"] && @$_POST["postCategories"]){
        $body = "#" . $_POST["postTitle"] . "\n" . $_POST["postContent"];
        $categories = array();
 
        preg_match_all("/\s*([\w\s]+),?/", $_POST["postCategories"], $categories);
 
        $post_content = "<!--" . $AtomGen->generateHeader(null, $_POST["postTitle"], $categories[1]) . "-->\n" . $body;
        $blog_meta = json_decode(file_get_contents($post_directory . "meta.json"));
 
        if (!file_exists($post_directory . str_pad($blog_meta->latest + 1, 4, "0", STR_PAD_LEFT) . ".md")) {
            $blog_meta->latest++;
            file_put_contents($post_directory . "meta.json", json_encode($blog_meta));
            file_put_contents($post_directory . str_pad($blog_meta->latest, 4, "0", STR_PAD_LEFT) . ".md", $post_content);
            echo "Successfully posted!";
        } else {
            echo "Error posting!";
        }
    }
    file_put_contents($post_directory . "atom.xml", $AtomGen->generateAtom($post_directory));
?>