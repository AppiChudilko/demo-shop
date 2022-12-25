<?php

namespace Server\Core;

if (!defined('AppiEngine')) {
    header( "refresh:0; url=/");
}
/**
 * Request
 */
class Request
{
    public function getRequest($params = array()) {

        $result = array();
        //$params = array_merge($params, json_decode(file_get_contents('config/request.json'), true));

        $arrayRequest = array(
            'index',
            'category',
            'category-',
            'category-hot',
            'category-top',
            'category-all',
            'item',
            'item-',
            'basket',
            'order-final',
            'info',
            'debug',
            'image',
            'name-',
            'width-',
            'height-',
            'admin',
            'admin/login',
        );

        $params = array_merge($params, $arrayRequest);

        if (empty($params)) return false;

        foreach ($params as $value) {
            if (preg_match('#/' . $value . '([^_/?]+)#', $_SERVER['REQUEST_URI'], $match)) {
                $result[$value] = $match[1];

                if ($value == 'category')
                    $result['p'] = 'category';
            }
            else if (preg_match('#^/?(' . $value . ')#', $_SERVER['REQUEST_URI'], $match)) {
                $result['p'] = $match[1];
            }
        }
        return $result;
    }
}