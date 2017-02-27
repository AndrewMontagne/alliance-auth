<?php
if (php_sapi_name() == "cli") {
    require_once("installer.php");
} else {
    require_once("index.php");
}