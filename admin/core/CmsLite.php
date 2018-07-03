<?php

namespace cms;

require_once 'Helper.php';

/**
 * Class CmsLite
 * @package cms
 */
class CmsLite
{
    /**
     * @var string
     */
    private $_file = 'core/config.json';

    /**
     * @var mixed
     */
    private $_json;

    /**
     * @var array
     */
    private $_defaults = [
        'title' => 'My Awesome Site',
        'meta_tags' => [
            'charset' => 'UTF-8'
        ]
    ];

    /**
     * @var array
     */
    private $_result = array(
        'success' => true,
        'error' => false,
        'type' => 'success'
    );

    /**
     * CmsLite constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * @param $name
     * @return mixed|string
     */
    public function get($name)
    {
        if(isset($this->_json[$name])) return $this->_json[$name];
        if(isset($this->_defaults[$name])) return $this->_defaults[$name];
        return '';
    }

    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name){
        $getter = 'get' . ucfirst($name);
        if (method_exists($this, $getter)) {
            return $this->$getter();
        } elseif (method_exists($this, 'set' . ucfirst($name))) {
            throw new \Exception('Getting write-only property: ' . get_class($this) . '::' . $name);
        } else {
            throw new \Exception('Getting unknown property: ' . get_class($this) . '::' . $name);
        }
    }

    /**
     * @return mixed|string
     */
    public function getMetaTags()
    {
        return $this->get('meta_tags');
    }

    /**
     * @return mixed|string
     */
    public function getOGTags()
    {
        return $this->get('og_tags');
    }

    /**
     * Register meta-tags on page
     * @return string
     */
    public function getRegisterMetaTags()
    {
        $r = '';
        $r .= $this->generateMetaTags();
        $r .= $this->generateOGTags();
        return $r;
    }

    public function getTitle()
    {
        return $this->get('title');
    }

    //TODO скорее всего можно объединить демо и обычную генерацию
    public function generateMetaTags()
    {
        $tags = $this->get('meta_tags');
        $result = '';
        //TODO проверить
        if(!empty($tags)){
            foreach ($tags as $name => $val){
                $name = trim($name);
                $value = trim($val);
                $name = strtolower($name);
                $name = htmlspecialchars($name);
                $value = htmlspecialchars($value);
                switch ($name){
                    case 'charset':
                        $result .= "<meta charset=\"".$value."\">\n";
                        break;
                    default:
                        $result .= "<meta name=\"".$name."\" content=\"".$value."\">\n";
                }
            }
        }
        return $result;
    }

    public function generateOGTags()
    {
        $tags = $this->get('og_tags');
        $result = '';
        //TODO проверить
        if(!empty($tags)){
            foreach ($tags as $name => $val){
                $name = trim($name);
                $value = trim($val);
                $name = strtolower($name);
                $name = htmlspecialchars($name);
                $value = htmlspecialchars($value);

                //TODO не факт, что везде будет так
                $result .= "<meta property=\"".$name."\" content=\"".$value."\">\n";
            }
        }
        return $result;
    }

    /**
     * Show prepare meta-tags
     * @param $data - output data from form
     * @return string
     */
    public function demoMetaTags($data)
    {
        $tags = $data;
        $result = '';
        if(!empty($tags)){
            foreach ($tags as $val){
                $name = trim($val['name']);
                $value = trim($val['value']);
                $name = strtolower($name);
                $name = htmlspecialchars($name);
                $value = htmlspecialchars($value);
                if(empty($value)) continue;

                switch ($name){
                    case 'charset':
                        $result .= "<meta charset=\"".$value."\">\n";
                        break;
                    default:
                        if($val['type'] == 'og_tags'){
                            $result .= "<meta property=\"".$name."\" content=\"".$value."\">\n";
                        } else {
                            $result .= "<meta name=\"".$name."\" content=\"".$value."\">\n";
                        }
                }
            }
        }
        $result = htmlspecialchars($result);
        return '<pre><code>'.$result.'</code></pre>';
    }

    /**
     * @param $action
     * @param $data
     * @return string
     */
    public function ajax($action, $data)
    {
        //TODO приводить в нижний регистр + экранирование
        switch ($action){
            case 'meta-tag-tab':
                if (isset($this->_json['meta_tags'])) unset($this->_json['meta_tags']);
                foreach ($data as $item){
                    if(empty(trim($item['value']))) continue;
                    $this->_set(trim($item['name']), trim($item['value']), trim($item['type']));
                }
                break;
            case 'og-tag-tab':
                if (isset($this->_json['og_tags'])) unset($this->_json['og_tags']);
                foreach ($data as $item){
                    if(empty(trim($item['value']))) continue;
                    $this->_set(trim($item['name']), trim($item['value']), trim($item['type']));
                }
                break;
            case 'demo-meta-tag':
                return $this->demoMetaTags($data);
            default:
                foreach ($data as $item){
                    $this->_set($item['name'], $item['value']);
                }
        }
        //TODO иная обработка ошибок
        $resultSave = $this->saveJSON();
        $resultSave = true; //TODO для теста
        if(!$resultSave){
            $this->_result['success'] = false;
            $this->_result['error'] = 'Не удалось сохранить настройки сайта! Попробуйте выполнить операцию позже или обратитесь в службу поддержки.';
            $this->_result['type'] = 'error';
        }
        return $this->prepare($this->_result);
    }


    /**
     * Save data in JSON
     * @return bool|int
     */
    private function saveJSON()
    {
        $data = json_encode($this->_json);
        $r = file_put_contents($this->_file, $data, LOCK_EX);
        if($r === false) return $r;
        return true;
    }

    private function init()
    {
        //TODO синглтон?
        $this->_file = Helper::normalizePath($this->_file);
        if(!file_exists($this->_file)){
            $default = json_encode($this->_defaults);
            file_put_contents($this->_file, $default);
        }
        $this->_json = file_get_contents($this->_file);
        $this->_json = json_decode($this->_json, true);
        //TODO проверка на корректный декодинг
    }

    private function prepare($result)
    {
        return json_encode($result);
    }

    private function _set($name, $value, $type = false)
    {
        if($type){
            $this->_json[$type][$name] = $value;
        } else {
            $this->_json[$name] = $value;
        }

    }

}