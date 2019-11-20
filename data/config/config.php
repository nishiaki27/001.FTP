<?php
    define ('ECCUBE_INSTALL', 'ON');
    define ('HTTP_URL', 'https://www.tokyo-aircon.net/');
    define ('HTTPS_URL', 'https://www.tokyo-aircon.net/');
    define ('ROOT_URLPATH', '/');
    define ('DOMAIN_NAME', '');
    define ('DB_TYPE', 'pgsql');
    define ('DB_USER', 'tokyo_aircon');
    define ('DB_PASSWORD', '7dgaCBAhptyrZaDT');
    define ('DB_SERVER', ' o4043-481.kagoya.net');
    define ('DB_NAME', 'fs_eccube');
    define ('DB_PORT', '5432');

    // kagoyaDB
    define ('AC_DB_USER', 'kir471336');
    define ('AC_DB_PASSWORD', 'mitaden123');
    define ('AC_DB_SERVER', 'mysqls51-16.kagoya.net');
    define ('AC_DB_NAME', 'kk_data');
    define ('AC_DB_PORT', '3306');

    define("ADMIN_DIR","adminpanel/");
define("ADMIN_FORCE_SSL",TRUE);
    define ('ADMIN_ALLOW_HOSTS', 'a:0:{}');
    define ('AUTH_MAGIC', 'crouphoheauakouceapoumouwaeweadraevaivea');
    define ('PASSWORD_HASH_ALGOS', 'sha256');
    
      // サーバー移行用
if ($_SERVER['SERVER_ADDR'] == '153.127.229.34') {
    define ('URLPATH_FULL', '/home/tokyo-aircon/public_html/ikou_www/eccube/');
} else {
    define ('URLPATH_FULL', '/home/tokyo-aircon/public_html/ikou_www/eccube/');
}
?>