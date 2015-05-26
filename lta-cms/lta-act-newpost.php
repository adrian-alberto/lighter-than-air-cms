<?php
    #
    #   TODO: Forgot to set $categories on line 20
    #

    session_start();
    if (!isset($_SESSION["isAdmin"])) {
        header("Location: http://" . $_SERVER["HTTP_HOST"] . "/lta/login");
        die();
    }

    require "lta-back.php";
    $LTACMS = new LighterThanAir();
    $post_directory = $_SERVER["DOCUMENT_ROOT"] .  "/content/posts/";

    if (@$_POST["postTitle"] && @$_POST["postContent"] && @$_POST["postCategories"]){
        $id = $LTACMS->getLatestPost() + 1;
        $title = $_POST["postTitle"];
        $body = "#" . $_POST["postTitle"] . "\n" . $_POST["postContent"];
        $categories = array();
        $file_name = str_pad($id, 4, "0", STR_PAD_LEFT) . "-" . preg_replace(array("<\s>", "<[^a-z0-9\-]>"), array("-",""), strtolower($title)) . ".md";
 
        preg_match_all("/\s*([\w\s]+),?/", $_POST["postCategories"], $categories);

        if (!file_exists($post_directory . $file_name)) {
            $post_content = $LTACMS->generateNextHeader($title, $categories, $id) . "\n" . $body;
            file_put_contents($post_directory . $file_name, $post_content);
            echo "Successfully posted!";
        } else {
            echo "Error posting!";
        }
    }
    #file_put_contents($post_directory . "atom.xml", $AtomGen->generateAtom($post_directory));
    header("Location: http://" . $_SERVER["HTTP_HOST"]);
?>