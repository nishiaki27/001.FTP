<?php
require_once(realpath(dirname( __FILE__)) . "/include.php");
require_once(realpath(dirname( __FILE__)) . "/class/LC_Page_Mdl_Gshopping_Config.php");

// }}}
// {{{ generate page

$objPage = new LC_Page_Mdl_Gshopping_Config();
register_shutdown_function(array($objPage, "destroy"));
$objPage->init();
$objPage->process();
