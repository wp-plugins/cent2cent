<?php
define("cent2cent_websiteid","cent2cent_websiteid");
define("cent2cent_websitesecret","cent2cent_websitesecret");
define("cent2cent_formwidth","cent2cent_formwidth");

define("cent2cent_startexp","(<p>)?\\[Cent2Cent\\]");
define("cent2cent_endexp","\\[\/Cent2Cent\\](<\/p>)?");

define("cent2cent_fullexp","/".cent2cent_startexp."(\d|\D)*".cent2cent_endexp."/");

define("cent2cent_start","[Cent2Cent]");
define("cent2cent_end","[/Cent2Cent]");
?>