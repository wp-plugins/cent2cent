<?php
require_once "cent2cent-data.php";

if( isset($_REQUEST["txtWebsiteID"]))
{
	update_option( cent2cent_websiteid, $_REQUEST["txtWebsiteID"] );
	update_option( cent2cent_websitesecret, $_REQUEST["txtWebsiteSecret"] );
	update_option( cent2cent_formwidth, $_REQUEST["txtFormWidth"] );
}

if(get_option( cent2cent_formwidth ) == null)
{
	update_option( cent2cent_formwidth, 500 );
}

?>

<style>
.C2CBox
{
	width:300px;
	padding:5px;
}
</style>

<div class="wrap">
<form method="post">


<Table cellspacing=10 cellpadding=10 style="border:0px solid black;">
<tr>
	<td colspan=2>
		<h2>Cent2Cent Settings</h2>
	</td>
</tr>
<tr>
	<td colspan=2>
		<b>Please enter your account credentials:</b>
		(Get yor credentials <A href="http://platform.cent2cent.net/Admin/Credentials.aspx" target=_new>here</a>)	
		
	</td>
	<td align=right>
		<input type=button value="Register with Cent2Cent" style="margin-right:10px;padding:10px;" onclick="window.open('http://www.cent2cent.net/website/Customers/Registration/WebsiteRegistration.aspx');">
	</td>
</tr>

<tr>
	<td>Website ID:</td>
	<td><input type=text name=txtWebsiteID class=C2CBox value="<?php echo get_option( cent2cent_websiteid );?>"></td>
	<Td rowspan=2>
	
	</td>
	
</tr>
<tr>
	<td>Website Secret:</td>
	<td><input type=text name=txtWebsiteSecret class=C2CBox value="<?php echo get_option( cent2cent_websitesecret );?>"></td>
</tr>
<tr>
	<td valign=top>Form Width:</td>
	<td><input type=text name=txtFormWidth class=C2CBox value="<?php echo get_option( cent2cent_formwidth );?>"><br>
	(The width of the purchase form within the post. in Pixel e.g. 500)
	</td>
</tr>
<tr>
	<td>
		<input type=submit value="Save Changes" style="margin-right:10px;" class="button-primary">
	</td>
	<Td>
	<?php 
	if( isset($_REQUEST["txtWebsiteID"]))
	{
		echo "<script>alert('Settings are saved!');</script>";
	}
	?>		
	</td>
</tr>
</table>
</form>
</div>


