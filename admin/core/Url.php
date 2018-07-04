<?php

namespace core;

class Url
{
    public static function to($url)
    {
        return CmsLite::$app->request->getBaseUrl().'/'.$url;
    }

    public static function goHome()
    {
        return CmsLite::$app->request->getBaseUrl();
    }
}