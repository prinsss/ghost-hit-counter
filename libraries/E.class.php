<?php
/**
 * @Author: prpr
 * @Date:   2016-04-24 09:35:38
 * @Last Modified by:   prpr
 * @Last Modified time: 2016-04-24 09:35:58
 */

/**
 * Custom error handler
 */
class E extends Exception
{
    function __construct($message = "Error occured.", $code = -1) {
        parent::__construct($message, $code);
        $exception['errno'] = $this->code;
        $exception['msg'] = $this->message;
        exit(json_encode($exception));
    }
}
