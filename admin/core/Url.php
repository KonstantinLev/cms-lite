<?php

namespace core;

/**
 * Class Url
 * @package core
 */
class Url
{
    /**
     * Creates a URL based on the given parameters.
     * @param $url
     * @return string
     */
    public static function to($url)
    {
        return CmsLite::$app->request->getBaseUrl().'/'.$url;
    }

    /**
     * Creates a URL on the home page.
     * @return string
     */
    public static function goHome()
    {
        return CmsLite::$app->request->getBaseUrl();
    }
}