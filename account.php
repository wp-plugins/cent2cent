<?php
// Displays the users login form 
require_once "data.php";

// Init error message
$errorMsg = "";

// Form has been posted
if( isset($_REQUEST["GetCredentials"]))
{
	// Get user name and password
	$username = $_REQUEST["username"];
	$password = $_REQUEST["password"];

	// Execute login with the platformn
	$result = Cent2CentAPI::Login($username,$password,site_url());

	// If result is was not sucessfull
	if(!$result)
	{
		// set error message
		$errorMsg = "Invalid username or password, please try again.<br>";
		
		// Reset WP params
		update_option( cent2cent_websiteid, null );
		update_option( cent2cent_websitesecret, null );
	}
	else
	{
		// Set wordpress params
		update_option( cent2cent_websiteid, $result->WebsiteID );
		update_option( cent2cent_websitesecret, $result->Secret );		
	}
}
echo "<script language=javascript src=\"".plugins_url( 'manager.js' , __FILE__ )."\"></script>"; 
?>

<style>
.C2CBox
{
	width:300px;
	padding:5px;
}
</style>
<script>
// Let the users change his user info
function ChangeInfo()
{
	jQuery("#divInfo").hide();
	jQuery("#divLogin").slideDown();
	
}
</script>

<div class="wrap">

<h2>Cent2Cent Plugin Configuration</h2>

<form method="post" action="">
<input type="hidden" name="GetCredentials" value="true" />

<?php 
// Check if user already entered data
$InfoExists = get_option( cent2cent_websiteid );
$InfoExists = !empty($InfoExists);

// This is displayed on if info does nto exist
?>
<Table cellspacing=10 cellpadding=10 style="border:0px solid black;display:<?php if($InfoExists) echo "none"; else echo "block";?>" id="divLogin">
<tr>
	<td colspan=2>
		<b>Please login with your Cent2Cent account credentials:</b>
		
	</td>
	<td align=right>
		<a href="#" onclick="Cent2Cent.ShowPopUp('http://www.cent2cent.net/website/Customers/Registration/3rdparty/Registration3rdParty.aspx',640,560);return false;">Register with Cent2Cent</a>
	</td>
</tr>

<tr>
	<td>Username:</td>
	<td><input type=text name=username class=C2CBox></td>
</tr>
<tr>
	<td>Password:</td>
	<td><input type=password name=password  class=C2CBox></td>
</tr>

<tr style="display:none;">
	<td valign=top>Form Width:</td>
	<td><input type=text name=txtFormWidth class=C2CBox value="<?php echo get_option( cent2cent_formwidth );?>"><br>
	(The width of the purchase form within the post. in Pixel e.g. 500)
	</td>
</tr>
<tr>
	<td colspan=2>
		<?php
			if($InfoExists)
			{
				echo "<b>Notice</b>: If you will change your account all of your setting will be lost.<br>";
			}
		?>
		<input type=submit value="Login" style="margin-top:10px;" class="button-primary">
		<div style="margin-top:10px;">
		<font color=red><?php echo $errorMsg;?></font>
		</div>
	</td>
</tr>
</table>

<?php
require_once "jsinclude.php";
// 	If page exists.. display success and some info
 ?>

<div id="divInfo" style="display:<?php if($InfoExists) echo "block"; else echo "none";?>">
<h2>All Done! </h2>
Now, earning money for your contents is just a few steps away.
Start by going to one of your posts or go to your 
<a href="#" onclick="Cent2Cent.ViewAdmin();return false;">account admin</a>

<br><br>
<b>Account Details</b>
<table cellpadding=0 cellspacing=0 style="margin-top:10px;margin-bottom:10px;">
<tr>
	<td style="width:120px;">Website ID:</td>
	<td><?php echo get_option( cent2cent_websiteid );?></td>
	
</tr>
<tr>
	<td>Website Secret:</td>
	<td><?php echo get_option( cent2cent_websitesecret );?></td>
</tr>
<tr>
	<td colspan=2><input type=button onclick=ChangeInfo(); value="Change Account" style="margin-right:10px;margin-top:10px;" class="button-primary"></td>

</table>
</div>

</form>
</div>


