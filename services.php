<?php
/*
 * Created on Jun 2, 2011
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
require_once "data.php";
require_once "cent2cent-API.php";

require_once "../../../wp-load.php";

Cent2CentAPI::$WebsiteID = get_option( cent2cent_websiteid  ); 
Cent2CentAPI::$Secret = get_option( cent2cent_websitesecret );

// Check credentials
if(!Cent2CentAPI::IsLoggedIn())
{
	echo "false";
	die();	
}
else if(!isset($_REQUEST["WebsiteId"]) || !isset($_REQUEST["Secret"]))
{
	echo "false";
	die();
}
else if($_REQUEST["WebsiteId"] != Cent2CentAPI::$WebsiteID || Cent2CentAPI::$Secret != Cent2CentAPI::$Secret)
{
	echo "false";
	die();
}

if(isset($_REQUEST["isWorking"]))
{
	echo "true";
	die();
}

if(isset($_REQUEST["setPost"]))
{
	$posts = explode(",",$_REQUEST["setPost"]);
	foreach ($posts as $post_id)
	{
		$queried_post = get_post($post_id);
			
		// Get content
		$content = $queried_post -> post_content;
		
		// Check if exists
		$pattern = "/".cent2cent_startexp."(\d|\D)*".cent2cent_endexp."/";
	
		// If nothing was defined
		if(!preg_match($pattern,$content))
		{
			// Update query
			$queried_post -> post_content = cent2cent_start.$content.cent2cent_end;
			wp_update_post($queried_post);
		}
	}
	echo "true";
	die();
}

// Get params
$search_string = null;
if(isset($_REQUEST["s"]))
{
	$search_string = $_REQUEST["s"]; 
}

$page_size = 100;
if(isset($_REQUEST["page_size"]))
{
	$page_size = $_REQUEST["page_size"];
}

$paged = 1;
if(isset($_REQUEST["paged"]))
{
	$paged = $_REQUEST["paged"];
}

$args = array(
	's' => $search_string,
	'post_type' => array('page',',post'),
	'posts_per_page'      => $page_size,
	'order'    => 'DESC',
	'paged' => $paged
);

$featuredPosts = new WP_Query();
$featuredPosts->query($args);
		
// The Query
//query_posts( $args );

  $doc = new DOMDocument();
  $doc->formatOutput = true;
  
  $r = $doc->createElement( "posts" );
  $doc->appendChild( $r );
  
	// The Loop
	while ( $featuredPosts -> have_posts() ) : $featuredPosts -> the_post();
		
		$postElement = $doc->createElement( "post" );
		
		// Create path
		$path = $doc->createElement( "path" );
  		$path->appendChild(  $doc->createTextNode( $post->guid )  );
		$postElement->appendChild($path);
		
		// Create title
		$title = $doc->createElement( "title" );
  		$title->appendChild(  $doc->createTextNode( $post->post_title )  );
		$postElement->appendChild($title);		
		
		$r->appendChild($postElement);

	endwhile;

echo $doc->saveXML();
?>
