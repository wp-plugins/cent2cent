<?php
// http://platform.cent2cent.net/Services/ClientServices/Create/json/?PATH={0}&SITEID={1}&META={2}
// http://platform.cent2cent.net/Services/ClientServices/verify/json/?SITEID={0}&REQUESTID={1}&CODE={2}&PATH={3}
class Cent2CentAPI
{
	public static $WebsiteID ;
	public static $Secret;
	public static $CheckURLPattern = "";
	public static $CheckURLOnly = true;
	public static $BaseURL = "http://platform.sandbox.cent2cent.net/";
	
	public static function CrossDomainCheck()
	{
		// Cross domain
		if(isset($_REQUEST["C2CInHandler"]))
		{
			?>
			<script language="javascript">
				parent.parent.Cent2Cent.HandleResponse(document.location.search);
			</script>
			<?php 
			die();
		}
	}
	

	public static function WriteHeader()
	{
		echo "<script language=javascript src=\"http://cdn.cent2cent.net/Insite/C2CPlatform.js\"></script>\n";
	}
	
	public static function CurrentURL()
	{
		 $pageURL = 'http';
		 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		 $pageURL .= "://";
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		 }
		 return $pageURL;
	}
	
	private static function HttpGet($sFilename)
	{
		//echo $sFilename."<br>";
		if (floatval(phpversion()) >= 4.3) {
			$sData = file_get_contents($sFilename);
		} else {
			if (!file_exists($sFilename)) return -3;
			$rHandle = fopen($sFilename, 'r');
			if (!$rHandle) return -2;

			$sData = '';
			while(!feof($rHandle))
				$sData .= fread($rHandle, filesize($sFilename));
			fclose($rHandle);
		}
		return $sData;
	}

	public static function Create($Identifier,$MetaData) 
	{ 	
		// Parse data
		$Identifier = urlencode($Identifier);
		
		$strCreate = self::$BaseURL."Services/ClientServices/Create/json/?PATH=%1s&SITEID=%2d";
		
		if(self::$Secret != null)
		{
			$strCreate .= "&Secret=%3s";
		}
		
		if($MetaData != null)
		{
			$UserToken = $_SESSION["C2CUserToken"];
			$strCreate .= "&META=%4s";
		}

		$UserToken = "";
		if(isset($_SESSION["C2CUserToken"]))
		{
			$UserToken = $_SESSION["C2CUserToken"];
			$strCreate .= "&UserToken=%5s";
		}
		
		$strCreate = sprintf($strCreate, $Identifier, self::$WebsiteID,self::$Secret,$MetaData,$UserToken);
		$data = self::HttpGet($strCreate);

		if($data == false)
		{
			return false;
		}
		else
		{
			$result = json_decode($data);
			return $result;
		}
	}
	
	public static function Verify($Identifier)
	{
		if(isset($_REQUEST["C2CInHandler"]))
		{
			return false;
		}

		if(!isset($_REQUEST["C2CRequestID"]) || !isset($_REQUEST["C2CApprove"]))
		{
			return false;
		}
		
		$requestID = $_REQUEST["C2CRequestID"];
		$token = $_REQUEST["C2CApprove"];
		
		$strVerify = self::$BaseURL."Services/ClientServices/verify/json/?SITEID=%1d&REQUESTID=%2s&CODE=%3s";
		
		if(self::$Secret != null)
		{
			$strVerify .= "&Secret=%4s";
		}

		
		if($Identifier != "") $strVerify .= "&PATH=%5s";
		$strVerify = sprintf($strVerify, self::$WebsiteID,$requestID,$token,self::$Secret,$Identifier);
		
		$data = self::HttpGet($strVerify);
		if($data == false)
		{
			return false;
		}
		else
		{
			$result = json_decode($data);
			
			//$_SESSION["C2CUserToken"] = $result -> UserToken;
			return $result;
		}
	}
	
	public static function CreateOrVerify($Identifier,$Params)
	{
		$Params = urlencode($Params);
		
		$result = self::Verify($Identifier);

		if($result == false || $result -> Status != "Approved")
		{
			$result = self::Create($Identifier,$Params);
		}
		
		$strRedirectURL = "";
		$strUserData = "";
		$strContentFrameID = "";
		$ContentToken = "";
		
		// Redirect URL
		if(isset($result -> RedirectURL))
		{
			$strRedirectURL = $result -> RedirectURL;
		}
		
		// User data
		if(isset($result -> UserData))
		{
			$strUserData =  $result -> UserData;
		}

		if(isset($result -> ContentFrameID))
		{
			$strContentFrameID =  $result -> ContentFrameID;
		}
		
		if(isset($result -> ContentToken))
		{
			$ContentToken = $result -> ContentToken;
		}
		
		$ShowContent = "false";
		if($result -> Status == "Invalid")
		{
			die("Invalid request - Please verify your secret key");
		}
		else if($result -> Status == "Approved" || $result -> Status == "Free")
		{
			$ShowContent = "true";
		}

		$arr = array ('ShowContent'=> $ShowContent,
						'RedirectURL' => $strRedirectURL , 
						'UserData' => $strUserData , 
						'ContentFrameID' => $strContentFrameID,
						'ContentToken' => $ContentToken);
		$result = json_decode(json_encode($arr));
		//print_r($result);
		return $result;
	}
	
	public static function StartPurchase($strURL,$strWidth,$strFrameID)
	{
		return "<script language=javascript>
				Cent2Cent.WriteIframe(\"".$strURL."\",\"".$strWidth."\",0,\"".$strFrameID."\");
				</script>";
	}
	
	public static function GenerateKalturaSession($secret,$partnerId,$entryId,$strServiceURL = "http://www.kaltura.com/")
	{
		$strServiceURL .= "/api_v3/?service=session&action=start";

		$postdata = http_build_query(
			array(
				'secret' => $secret,
				'type' => 'USER',
				'partnerId' => $partnerId,
				'privileges' => 'sview:'.$entryId,
				'expiry' => 86400,
				'timestamp' => time()
			)
		);

		$opts = array('http' =>
			array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => $postdata
			)
		);
		
		$context  = stream_context_create($opts);
		$result = file_get_contents($strServiceURL, false, $context);
		$doc = new DOMDocument();
		$doc->loadXML($result);

		return $doc->getElementsByTagName('result')-> item(0) -> nodeValue;
	}	
}

?>