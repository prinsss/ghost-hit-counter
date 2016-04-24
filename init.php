<?php
/**
 * @Author: prpr
 * @Date:   2016-04-24 09:36:31
 * @Last Modified by:   prpr
 * @Last Modified time: 2016-04-24 09:36:58
 */

session_start();

require "config.php";
require "./libraries/Database.class.php";
require "./libraries/Counter.class.php";
require "./libraries/E.class.php";

header('Access-Control-Allow-Origin: *');
header("Content-type: text/json");
