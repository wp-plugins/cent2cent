<?php

// This is the display page that is being requsted by contentitem.php to display everything

require_once "data.php";
require_once "cent2cent-API.php";
require_once "../../../wp-load.php";


Cent2CentAPI::$WebsiteID = get_option( cent2cent_websiteid ); 
Cent2CentAPI::$Secret = get_option( cent2cent_websitesecret );

// If user is not logged in!
if(!Cent2CentAPI::IsLoggedIn())
{
	?>
	<div style="text-align:center;line-height:30px;">
	Thank you for using Cent2Cent!<br>
	Please <a href="#" onclick="Cent2Cent.ShowLogin();return false;">Login</a> or <a href="#" onclick="Cent2Cent.ShowPopUp('http://www.cent2cent.net/website/Customers/Registration/3rdparty/Registration3rdParty.aspx',640,560);return false;">Register</a>
	</div>
	<?php
	die();
}

// If only status has been requsted
if(isset($_REQUEST["status"]))
{
	Cent2CentAPI::SetItemStatus($_REQUEST["itemid"],$_REQUEST["status"]);
	die();
}

	
// Get details
$result = Cent2CentAPI::GetItemDetailsByUrl($_REQUEST["url"]);
$refreshImageURL = plugins_url( 'images/reload.png' , __FILE__ );
?>

<div id="pnlChangeSelection" style="line-height:20px;display:none;" >
Please select the content section for sale or don't select anything if the entire post is for sale. 
<br>
<table width=100%>
<tr>
	<td><a href="#" onclick="Cent2Cent.CancelSelection();return false;">Cancel</a></td>
	<Td align=right><input type=button value="Select" class="button-primary" onclick="Cent2Cent.ConfirmSelection();return false;" /></td>
</tr>
</table>

</div>

<div style="line-height:20px;" id="pnlAll">
<div style="float:right;margih:10px;">
<a href="#" onclick="Cent2Cent.GetContentItemDetails();return false;">
<img src="<?php echo $refreshImageURL;?>" style="border:0px;" /></a>
</div>
<?php 
	global $current_user;
    get_currentuserinfo();

if ($result -> ID == 0)
{
	echo "Hi ".$current_user->user_login." (<a href='#' onclick=\"Cent2Cent.ViewAdmin();return false;\">view account admin</a>),";
?>

<div style="margin-top:10px;" id="pnlNoContentItem">
Please select the content section for sale  or don't select anything if the entire post is for sale.
</div>

<div style="text-align:center;">
<input type=button value="Continue >" class="button-primary" onclick="Cent2Cent.NewContentItem();"">
</div>
<?php
}
else
{
	$divStyle = "";
	$info = "(Item for sale)";
	if(!$result -> IsActive)
	{
		$divStyle = "opacity: 0.3;filter: alpha(opacity=3);";
		$info = "(Free of charge)";
	}
?>
	<div style="margin-bottom:10px;margin-top:10px;">
	<b>Status</b>: <?php echo ($result -> IsActive ? "Active" : "Not Active")." ".$info;?>
	</div>
		
		
	<div style="<?php echo $divStyle;?>">

		<div style="margin-bottom:10px;">
		<b>Sale Section</b>: 
		<span id="pnlSelection">
		Analyzing...
		</span>
		(<a href="#" onclick="Cent2Cent.ConfirmSelection();return false;">Change</a>)
		</div>
		
	
		<div style="margin-bottom:10px;"><b>Offerings</b><br>
		<?php
		for($i = 0; $i < sizeof($result -> Offers); ++$i)
		{
			$offer = $result -> Offers[$i];
			echo ($i+1).". ".$offer -> Name.",".$offer -> CurrencySymbol.$offer -> Price."<br>";
		}
		?>
		</div>
	
		<div style="margin-bottom:10px;">
		<b>Sales:</b><br>
		Today (<?php echo $result -> CurrencySymbol.$result -> TodaySales; ?>), 
		Weekly (<?php echo $result -> CurrencySymbol.$result -> WeeklySales; ?>), 
		Monthly (<?php echo $result -> CurrencySymbol.$result -> MonthSales; ?>)
		</div>

	</div>
	<div>
	<a href="#" onclick="Cent2Cent.QuickEdit(<?php echo $result -> ID;?>);return false;">Quick Edit</a>
	&nbsp;|&nbsp;
	<a href="#" onclick="Cent2Cent.EditItem(<?php echo $result -> ID;?>);return false;">Advanced Edit</a>
	&nbsp;|&nbsp;
	<a href="#" onclick="Cent2Cent.SetStatus(<?php echo $result -> ID;?>,<?php echo ($result -> IsActive ? "false" : "true") ?>);return false;">
	<?php echo ($result -> IsActive ? "Deactivate" : "Activate");?>
	</a>
	</div>
	
<?php }
?>
</div>
