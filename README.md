_WARNING: I haven't finished ripping all of the code off of my website yet._

#Lighter Than Air CMS
The blog content management system for elitist asshole developers.
It's dynamic because you don't get enough traffic to warrant static webpages.
It's flat-file because I don't know how to work with databases and you probably don't enjoy it much.
It has a php interface because you don't want to learn how to use it like with other elitist asshole content management systems.

##Key Features:
 * Minimum overhead
 * Blog post metadata
 * Blog posting interface
 * No databases (file operated)

##Things I need to do:
 * Secure the login & reimplement posting
 * Add category groups
 * Refactor everything
 * Refactor everything again
 * Blog post editing/removal interface
 * Re-implement Atom feed generation 


##CMS Architecture
	Root
		content
			posts
				0000-test-post.md
				0001-default-title.md
				0002-second-post.md
			images
				test.png
			blog-meta.json
			atom.xml
		css
			example.css
		js
			jquery-1.11.2.min.js
			pagedown.js
		lta-cms
			interface
				login.php
				newpost.php
			lta-back.php
			lta-login-form
			parsedown.php
		index.php
		.htaccess

##Blog post metadata format
	{
		"TITLE": "Default",
		"DATE": 0,
		"UPDATED": 0,
		"ID": "tag:whitecollargames.com,1970-01-01:0:1",
		"CATEGORIES":[]
	}
