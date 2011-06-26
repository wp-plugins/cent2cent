<?php
/*
 * Created on Jun 5, 2011
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 require_once "data.php";
 require_once "cent2cent-API.php";
 require_once "jsinclude.php";
?>


<script type="text/javascript">
jQuery(document).ready(
    function () {
		Cent2Cent.GetContentItemDetails();
		
		// Validate selection every 2 seconds
		setInterval("Cent2Cent.SetSelectionMode()",2000);
		
		var obj = jQuery("#pnlCent2CentPopUp");
		jQuery(document.body).append(obj);
    }
);
</script>


<!-- this is the content panel -->
<div id="pnlCent2Cent"></div>

<!-- popup pages -->
<div style="position:fixed;top:0px;bottom:0px;right:0px;left:0px;z-index:9998;border:0px solid black;display:none;" id="pnlCent2CentPopUp">
	<div style="position:absolute;top:0px;bottom:0px;right:0px;left:0px;border:0px solid pink;background-color:gray;opacity: 0.80;filter: alpha(opacity=80);">
	</div>
	
	<div style="position:absolute;top:0px;bottom:0px;right:0px;left:0px;z-index:9998;">
	<table width=100% height=100%>
	<tr>
		<td align=center valign=middle>
			<iframe width=620 height=550 id=fraCent2Cent name=fraCent2Cent scrolling=no allowtransparency=true frameborder=0 style="z-index:9999"></iframe>
		</td>
	</tr>
	</table>
	
	</div>
</div>

