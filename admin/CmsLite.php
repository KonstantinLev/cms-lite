<?php

namespace cms;

/**
 * Class CmsLite
 * @package cms
 */
class CmsLite
{
    /**
     * @var string
     */
    private $_file = 'config.json';

    /**
     * @var mixed
     */
    private $_json;

    /**
     * @var array
     */
    private $_defaults = array(
        'title' => 'MySite',
        'charset' => 'UTF-8',
    );

    public function __construct()
    {
        //TODO синглтон
        if(!file_exists($this->_file)){
            $fp = fopen($this->_file, "w");
            fclose($fp);
            $default = json_encode($this->_defaults);
            file_put_contents($this->_file, $default);
        }
        $this->_json = file_get_contents($this->_file);
        $this->_json = json_decode($this->_json, true);
        //TODO проверка на корректный декодинг
    }

    public function get($name)
    {
        if(isset($this->_json[$name])) return $this->_json[$name];
        if(isset($this->_defaults[$name])) return $this->_defaults[$name];
        return '';
    }

    public function save($action, $data)
    {
        var_dump($action);
        var_dump($data);
        return '';
    }

}