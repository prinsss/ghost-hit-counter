<?php
/**
 * @Author: printempw
 * @Date:   2016-01-31 17:08:29
 * @Last Modified by:   prpr
 * @Last Modified time: 2016-04-24 09:47:09
 */

require "bootstrap.php";

if (isset($_GET['action'])) {
    // Simple SQL injection prevention
    $action = stripslashes(trim($_GET['action']));
    $slug = isset($_GET['slug']) ? stripslashes(trim($_GET['slug'])) : "";

    // Instantiate objects
    $database = new Database(DB_TYPE);
    $counter = new Counter($database);

    if ($action == "get") {
        $count = $counter->get($slug);
        if (!$count) throw new E('No such slug.', 0);

        $json['slug'] = $slug;
        $json['count'] = $count;
    } else if ($action == "add") {
        // Simple prevention for evil post
        if (!isset($_SESSION['last_post'])) {
            $_SESSION['last_post'] = time();
        } else {
            $post_time_cap = time() - $_SESSION['last_post'];
            if ($post_time_cap < TIME_CAP) {
                throw new E('Wow you so faaaaaaaaast! Wait for some secs, man.', 1);
            }
        }
        $count = $counter->add($slug);
        if (!$count) throw new E('No such slug.', 0);
        $json['slug'] = $slug;
        $json['count'] = $count;

        $_SESSION['last_post'] = time();
    } else if ($action == "order") {
        // Check input
        $limit = (isset($_GET['limit']) && is_numeric($_GET['limit'])) ? $_GET['limit'] : 10;
        echo $counter->getPopularSlug($limit);
    }
    // Dont forget to close the connection :)
    $database->close();
} else {
    throw new E('Invalid parameters.', -1);
}

echo isset($json) ? json_encode($json) : "";
