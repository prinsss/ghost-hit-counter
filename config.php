<?php

/* Database type, `mysql` or `sqlite` */
define('DB_TYPE',   'mysql');

/* Configure following info if you use MySQL */
/* Database name should be same with which you set for ghost */
define('DB_NAME',   'ghost');
define('DB_USER',   'root');
define('DB_PASSWD', 'root');
define('DB_PORT',   3306);
define('DB_HOST',   'localhost');

/* For SQLite users, please configure the database path */
define('DB_DIR',   'ghost-dev.db');

/* Allowed time cap between each addition request in seconds */
define('TIME_CAP',  5);
