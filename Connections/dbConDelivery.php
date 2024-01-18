<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_dbConDelivery = "localhost:3307";
$database_dbConDelivery = "delivery";
$username_dbConDelivery = "root";
$password_dbConDelivery = "";
$dbConDelivery = mysql_pconnect($hostname_dbConDelivery, $username_dbConDelivery, $password_dbConDelivery) or trigger_error(mysql_error(), E_USER_ERROR);
?>