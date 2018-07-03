<?php

namespace core;

class Router
{
    private $_defaultRoute = 'main';

    private $_route;

    public function route()
    {
        $request = $_SERVER['REQUEST_URI'];
        $requestParts = explode('?', $request);
        $this->_route = str_replace(CmsLite::$app->getBaseUrl().'/', '',$requestParts[0]);
        $pathParts = explode('/', $this->_route);
        return $this->_route === '' ? $this->_defaultRoute : $this->_route;
    }
}