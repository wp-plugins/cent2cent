<?php
/*
 * Created on Jun 23, 2011
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
  echo "<script language=javascript src=\"".plugins_url( 'manager.js' , __FILE__ )."\"></script>"; 
?>

<script type="text/javascript">

// Set params
Cent2Cent.SiteURL = "<?php echo site_url();?>";
Cent2Cent.CurrentPost = "<?php global $post; echo $post -> guid;?>"; 
Cent2Cent.PlatformURL = "<?php echo Cent2CentAPI::$BaseURL;?>";
Cent2Cent.PostTitle = "<?php global $post; echo $post -> post_title;?>";

Cent2Cent.CENT2CENT_START = "<?php echo cent2cent_start; ?>";
Cent2Cent.CENT2CENT_END = "<?php echo cent2cent_end; ?>";
Cent2Cent.CENT2CENT_FULL_EXP = "<?php echo cent2cent_fullexp; ?>";
Cent2Cent.CENT2CENT_NEW = "New]";

Cent2Cent.WebsiteID = "<?php echo get_option( cent2cent_websiteid ); ?>";
Cent2Cent.Secret = "<?php echo get_option( cent2cent_websitesecret );?>";
Cent2Cent.WEBSITE_PARAM = "WebsiteId";
Cent2Cent.WEBSITE_SECRET_PARAM = "Secret";
</script>
