<?php
/*
Plugin Name: Cent2Cent.net
Plugin URI: http://www.cent2cent.net
Description: Content Into Profit. Easy.
Version: 1.0.0
Author: Cent2Cent.net 
Author URI: http://www.cent2cent.net
License: FREE
*/

require_once "data.php";
require_once "cent2cent-API.php";

Cent2CentAPI::CrossDomainCheck();
Cent2CentAPI::$WebsiteID = get_option( cent2cent_websiteid ); 
Cent2CentAPI::$Secret = get_option( cent2cent_websitesecret ); 
define("Cent2CentWidth", "100%"); //get_option( cent2cent_formwidth ));

function filter_cent2cent_header() {
	Cent2CentAPI::WriteHeader();
	if(!Cent2CentAPI::IsLoggedIn())
	{
		return;
	}
	?>
	    <script language=javascript>
	    Cent2Cent.C2CBaseURL = "<?php echo Cent2CentAPI::$BaseURL?>";
		Cent2Cent.WebSiteID = <?php echo Cent2CentAPI::$WebsiteID?>;
        Cent2Cent.ShowContent = function (id, token) {
			
			var strURL = document.location.href;
			if(strURL.indexOf("?") == -1)
			{
				strURL += "?";
			} 
			else
			{
				strURL += "&";
			}        
			
			strURL += this.C2C_PARAM_REQUESTID + "=" + id + 
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

	if(!Cent2CentAPI::IsLoggedIn())
 	{
		return $content;
	}
			
	// If nothing was defined (no area)
	if(!preg_match(cent2cent_fullexp,$content))
	{
		return $content;
	}
	
	// Set the id of the post as param
	$meta =  '{ "id" : "'.$post->ID.'"}';
	$result = Cent2CentAPI::CreateOrVerify($post->guid,$meta);

	// Wrap div with Cent2Cent DIV (FFU)
	$html = "<div name=Cent2Cent id=WP".$post->ID." description=\"".urlencode($post -> post_title)."\">";
	
	// If do not show the content
	if($result -> ShowContent == "false")
	{
		// Get the purchase frame
		$frame = Cent2CentAPI::StartPurchase($result -> RedirectURL,Cent2CentWidth,$result -> ContentFrameID);
		
		// Check for match
		if(preg_match(cent2cent_fullexp,$content))
		{
			// Replace match with frame
			$html .= preg_replace(cent2cent_fullexp,$frame,$content);
		}
		else
		{
			// If no match (this should never happen. taken care with the check above)
			// if(!preg_match(cent2cent_fullexp,$content))
			$html .= $frame;
		}
	}
	else
	{
		// If content is free. remove the wrapping and display clean text to the user
		$content = preg_replace("/".cent2cent_startexp."/","<p>",$content);
		$content = preg_replace("/".cent2cent_endexp."/","</p>",$content);
		$html .= $content;
	}
		
	$html .= "</div>";
	return $html;
}


// Verify request (this comes from the JS after verify)
if(isset($_REQUEST["C2CApprove"]))
{
	session_start(); 

	$result = Cent2CentAPI::Verify("");
	if($result != false)
	{
		$UserData =  json_decode($result -> UserData);
	
		// Extract the post data		
		$post_id = $UserData -> id * 1;
		$queried_post = get_post($post_id);
		$content = $queried_post -> post_content;
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);

		// Fix cent2cent (to show clean text)
		$content = preg_replace("/".cent2cent_startexp."/","<p>",$content);
		$content = preg_replace("/".cent2cent_endexp."/","</p>",$content);
		
		// Return data
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
	// Set filters (if not in verify state)
	add_filter('the_content','filter_cent2cent_content');
	add_action('wp_head', 'filter_cent2cent_header');
}

// ----------------------------------------------------------------------------------------------------------------
//
//											Menu options
//
// ----------------------------------------------------------------------------------------------------------------

// Register the cent2cent menu to the plugin admin
add_action('admin_menu', 'cent2cent_plugin_menu');

// Register widget to post + page
add_action( 'admin_menu', 'cent2cent_add_custom_box' );

// Add settings within plugin menu
add_filter( 'plugin_action_links', 'cent2cent_plugin_action_links', 0, 2 );

// Run warrrning
cent2cent_admin_warnings();
function cent2cent_admin_warnings() {

	if(!Cent2CentAPI::IsLoggedIn() && !isset($_POST['submit']))
	{
		function cent2cent_warning() {
			echo "
			<div id='cent2cent-warning' class='updated fade'><p><strong>".__('Cent2Cent is almost ready.')."</strong> ".sprintf(__('You must login to Cent2Cent from your WordPress admin to complete the integration between the both platforms. You can do so by clicking on the plugin "<a href="%1$s">Settings</a>" link or either from one of your posts\pages.'), "plugins.php?page=Cent2CentSetting")."</p></div>
			";
		}
		add_action('admin_notices', 'cent2cent_warning');
		return;
	}
	
}

// Set setting link for cent2cent plugin
function cent2cent_plugin_action_links( $links, $file ) {
	if ( $file == plugin_basename( dirname(__FILE__).'/admin.php' ) ) {
		$links[] = '<a href="plugins.php?page=Cent2CentSetting">'.__('Settings').'</a>';
	}

	return $links;
}

// Set plugin 
function cent2cent_plugin_menu() {
	add_plugins_page('Cent2Cent', 'Cent2Cent', "manage_options", 'Cent2CentSetting', 'cent2cent_plugin_options');
}

// Display account.php in the settings page
function cent2cent_plugin_options() {
	include('account.php'); 
}

// Create hook for widget
function cent2cent_add_custom_box() {
	add_meta_box( 'cent2cent_contentitem_edit','Cent2Cent Content Edit',"cent2cent_add_custom_box_print", 'post', 'side','high' );
	add_meta_box( 'cent2cent_contentitem_edit','Cent2Cent Content Edit',"cent2cent_add_custom_box_print", 'page', 'side','high' );
}

// Display widget content
function cent2cent_add_custom_box_print(){
	
	include('contentitem.php'); 
}
?>