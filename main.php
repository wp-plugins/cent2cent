<?php
/*
Plugin Name: Cent2Cent.net
Plugin URI: http://www.cent2cent.net
Description: Content Into Profit. Easy.
Version: 0.9
Author: Cent2Cent.net 
Author URI: http://www.cent2cent.net
License: FREE
*/

require_once "cent2cent-data.php";
require_once "cent2cent-API.php";

Cent2CentAPI::CrossDomainCheck();
Cent2CentAPI::$WebsiteID = get_option( cent2cent_websiteid ); 
Cent2CentAPI::$Secret = get_option( cent2cent_websitesecret ); 
define("Cent2CentWidth", get_option( cent2cent_formwidth ));

function filter_cent2cent_header() {
	Cent2CentAPI::WriteHeader();
	?>
	    <script language=javascript>
		Cent2Cent.WebSiteID = <?php echo Cent2CentAPI::$WebsiteID?>;
        Cent2Cent.ShowContent = function (id, token) {
			
			var strURL = "?" + this.C2C_PARAM_REQUESTID + "=" + id + 
						 "&" + this.C2C_PARAM_APPROVAL + "=" + token;
			
			jQuery.get(strURL, function(data) {

				var result = JSON.parse(data);
				jQuery("#" + result.ContentFrameID).parent().html(result.Content);

			});
        };
    </script>

	<?php
}

function filter_cent2cent_content($content) {

	global $post;

	if(Cent2CentAPI::$WebsiteID == null || Cent2CentAPI::$WebsiteID == null || Cent2CentWidth == null)
	{
		die("<div style=text-align:left;line-height:30px;><h2 style=color:red;>Cent2Cent settings are missing!</h2>
						Please go to:<br>
						<font style=font-size:20px;font-weight:bold;>wp-admin -> Plugins -> Cent2Cent<br></font>
						and set all your details<br>
						Thank you!
			</div>");
	}
	
	$meta =  '{ "id" : "'.$post->ID.'"}';
	$result = Cent2CentAPI::CreateOrVerify($post->guid,$meta);

	$html = "<div name=Cent2Cent id=WP".$post->ID." description=\"".urlencode($post -> post_title)."\">";
	if($result -> ShowContent == "false")
	{
		$html .= Cent2CentAPI::StartPurchase($result -> RedirectURL,Cent2CentWidth,$result -> ContentFrameID);
	}
	else
	{
		$html .= $content;
	}
		
	$html .= "</div>";
	return $html;
}

function cent2cent_menu() {
	add_options_page('My Plugin Options', 'My Plugin', 'manage_options', 'my-unique-identifier', 'my_plugin_options');
}


// Verify request
if(isset($_REQUEST["C2CApprove"]))
{
	session_start(); 

	$result = Cent2CentAPI::Verify("");
	if($result != false)
	{
		$UserData =  json_decode($result -> UserData);
		
		$post_id = $UserData -> id * 1;
		$queried_post = get_post($post_id);
		$content = $queried_post -> post_content;
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);

		$arr = array ('ContentFrameID' => $result -> ContentFrameID,'Content' => $content);
		echo json_encode($arr);

	}
	else
	{
		echo "not found!";
	}
	die();
}
else
{
	add_filter('the_content','filter_cent2cent_content');
	add_action('wp_head', 'filter_cent2cent_header');
}

// ----------------------------------------------------------------------------------------------------------------
//
//											Menu options
//
// ----------------------------------------------------------------------------------------------------------------
add_action('admin_menu', 'cent2cent_plugin_menu');

function cent2cent_plugin_menu() {
	add_plugins_page('Cent2Cent', 'Cent2Cent', "manage_options", 'Cent2CentSetting', 'cent2cent_plugin_options');
}

function cent2cent_plugin_options() {
	include('cent2cent-settings.php'); 
}
?>