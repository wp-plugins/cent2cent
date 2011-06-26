<?php
require_once "data.php";
require_once "cent2cent-API.php";
require_once "../../../wp-load.php";

// This is the login page that is within a popup
$errorMsg = "";

if( isset($_REQUEST["GetCredentials"]))
{
	$username = $_REQUEST["username"];
	$password = $_REQUEST["password"];

	$result = Cent2CentAPI::Login($username,$password,site_url());

	if(!$result)
	{
		$errorMsg = "Invalid username or password, please try again.<br>";
		update_option( cent2cent_websiteid, null );
		update_option( cent2cent_websitesecret, null );
	}
	else
	{
		update_option( cent2cent_websiteid, $result->WebsiteID );
		update_option( cent2cent_websitesecret, $result->Secret );	
		?>
		<script type="text/javascript">
		top.Cent2Cent.WebsiteID = "<?php echo $result->WebsiteID; ?>";
		top.Cent2Cent.Secret = "<?php echo $result->Secret; ?>";
		top.Cent2Cent.ClosePopUp();
		</script>
		<?php	
		die();
	}
}
?>

<style>
.C2CBox
{
	width:300px;
	padding:5px;
}

body,TD
{
    font-size:12px;
    font-family:Arial;
    line-height:20px;
    color:#041c3e;
}
.PopUpHeader
{
    border-top:1px solid #d3d3d3;
    border-left:1px solid #d3d3d3;
    border-right:1px solid #d3d3d3;
    border-bottom:0px solid #d3d3d3;
    background-color:#f5f3f4;
    font-weight:bold;
    text-align:center;
    color:#041c3e;
    text-align:left;
    padding:10px;
}

.PopContent
{
    border:1px solid #d3d3d3;
}
</style>

<div style="background-color:white;padding:0px;width:440px;border:0px solid black;padding:2px;">

<div class="PopUpHeader">
Cent2Cent - Login
</div>

<div class=PopContent>

<Table cellspacing=5 cellpadding=5 style="border:0px solid black;display:<?php if($InfoExists) echo "none"; else echo "block";?>" id="divLogin">
<form method="post" action="">
<input type="hidden" name="GetCredentials" value="true" />
<tr>
	<td colspan=2>
		<b>Please login with your Cent2Cent account credentials:</b>
	</td>
</tr>

<tr>
	<td>Username(email):</td>
	<td><input type=text name=username class=C2CBox></td>
</tr>
<tr>
	<td>Password:</td>
	<td><input type=password name=password  class=C2CBox></td>
</tr>
<tr>
	<td colspan=2 align=left>

		<div style="float:right">
		<input type=submit value="Login" style="margin-top:0px;" class="button-primary">
		</div>
		<font color=red><?php echo $errorMsg;?></font>
	</td>
</tr>

</form>
</table>
</div>


</div>


