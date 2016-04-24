<?php
/**
 * @Author: prpr
 * @Date:   2016-04-24 08:34:10
 * @Last Modified by:   prpr
 * @Last Modified time: 2016-04-24 09:34:28
 */

class Counter
{
    private $db = null;

    function __construct(Database $db) {
        if (!is_null($db)) {
            $this->db = $db;
        } else {
            throw new E('Invalid given Database instance.', 3);
        }
    }

    /**
     * Add page view of given slug
     *
     * @param  string $slug
     * @return bool
     */
    public function add($slug) {
        $result = $this->db->query("SELECT * FROM posts WHERE slug='$slug'");
        if ($this->checkSlugExist($slug)) {
            $this->db->query("UPDATE post_views SET pv=pv+1 WHERE slug='$slug'");
            return $this->get($slug);
        } else {
            return false;
        }
    }

    /**
     * Get page view of given slug
     *
     * @param  string $slug
     * @return int
     */
    public function get($slug) {
        if ($this->checkSlugExist($slug)) {
            $result = $this->db->query("SELECT * FROM post_views WHERE slug='$slug'");
            $count = $this->db->fetchArray($result)['pv'];
            return $count;
        } else {
            return false;
        }
    }

    public function getPopularSlug($limit) {
        $order_sql = "SELECT * FROM `post_views` ORDER BY `pv` DESC LIMIT $limit";
        $title_sql = "SELECT * FROM `posts` WHERE `slug`=";
        $popular_posts = [];
        $result = $this->db->query($order_sql);
        while ($row = $this->db->fetchArray($result)) {
            $slug  = $row['slug'];
            $title_result = $this->db->query($title_sql."'$slug'");
            $title = $this->db->fetchArray($title_result)['title'];
            $popular_posts[$slug] = [$title, $row['pv']];
        }
        echo json_encode($popular_posts);
    }

    /**
     * Check if given slug exists
     * @param  string $slug
     * @return bool
     */
    public function checkSlugExist($slug) {
        $sql = "SELECT * FROM post_views WHERE slug='$slug'";
        if ($this->db->checkRecordExist($sql)) {
            return true;
        } else {
            $sql = "SELECT * FROM posts WHERE slug='$slug'";
            /**
             * If requested slug doesnt exist in `post_views` but exist in `posts`,
             * then insert a record.
             */
            if ($this->db->checkRecordExist($sql)) {
                return $this->insertRecord($slug);
            } else {
                // Non-existent slug
                return false;
            }
        }
    }

    public function insertRecord($slug) {
        $sql = "INSERT INTO post_views(slug, pv) VALUES ('$slug', 1)";
        return $this->db->query($sql) ? true : false;
    }
}
