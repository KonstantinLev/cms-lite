<?php

namespace core;

/**
 * Class CmsLite
 * @property View view
 * @package cms
 */
class CmsLite
{
    /**
     * @var string
     */
    private $_file = 'core/config/config.data';

    /**
     * @var mixed
     */
    private $_json;

    /**
     * @var array
     */
    private $_defaults = [
        'access' => [
            'login' => 'test',
            'pwd' => '123'
        ],
        'title' => 'My Awesome Site',
        'phone' => '',
        'email' => '',
        'meta_tags' => [
            'charset' => 'UTF-8'
        ],
        'og_tags' => []
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
     * @var User
     */
    private $_user;

    /**
     * @var Router
     */
    private $_router;

    /**
     * @var View
     */
    private $_view;

    private $_baseUrl;
    private $_scriptUrl;
    private $_scriptFile;

    /**
     * @var
     */
    public static $app;

    /**
     * CmsLite constructor.
     */
    public function __construct()
    {
        //TODO singleton?
        $this->init();
        $this->_user = new User();
        $this->_view = new View();
        $this->_router = new Router();
        static::$app = $this;
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function run()
    {
        $this->view->set('cms', $this);
        $route = $this->_router->route();
        //TODO если не вводили
        if($this->getUser()->isLoggedIn()){
            $this->view->display($route);
        } else {
            $this->view->display('auth');
        }

    }

    public function getView()
    {
        return $this->_view;
    }

    /**
     * @param $type
     * @return string
     */
    public function drawMetaBlocks($type)
    {
        if($type === 'meta_tags'){
            $placeholderName = 'example: charset';
            $placeholderValue = 'example: UTF-8';
        } else {
            $placeholderName = 'example: og:title';
            $placeholderValue = 'example: The Rock';
        }
        $default = '
<div class="block-meta-item">
    <div class="row">
        <input type="hidden" name="type" value="'.$type.'">
        <div class="col-md-5">
            <div class="cl-form-group">
                <input type="text" placeholder="'.$placeholderName.'" class="cl-input" name="name">
            </div>
        </div>
        <div class="col-md-5">
            <div class="cl-form-group">
                <input type="text" placeholder="'.$placeholderValue.'" class="cl-input" name="value">
            </div>
        </div>
        <div class="col-md-2">
            <a href="#" class="meta-times" onclick="removeMetaBlock(this);"><i class="fal fa-times-circle"></i></a>
        </div>
    </div>
</div>';
        $data = $this->get($type);
        if(!empty($data)){
            $result = '';
            foreach ($data as $metaName => $metaVal){
                $result .= '
<div class="block-meta-item">
    <div class="row">
        <input type="hidden" name="type" value="'.$type.'">
        <div class="col-md-5">
            <div class="cl-form-group">
                <input type="text" placeholder="example: og:title" class="cl-input" name="name" value="'.$metaName.'">
            </div>
        </div>
        <div class="col-md-5">
            <div class="cl-form-group">
                <input type="text" placeholder="example: The Rock" class="cl-input" name="value" value="'.$metaVal.'">
            </div>
        </div>
        <div class="col-md-2">
            <a href="#" class="meta-times" onclick="removeMetaBlock(this);"><i class="fal fa-times-circle"></i></a>
        </div>
    </div>
</div>';
            }
            return $result;
        }
        return $default;
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
     * @return User
     */
    public function getUser()
    {
        return $this->_user;
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

    //TODO скорее всего можно объединить og и обычную генерацию
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
                    if(empty(trim($item['name'])) || empty(trim($item['value']))) continue;
                    $this->_set(trim($item['name']), trim($item['value']), trim($item['type']));
                }
                break;
            case 'og-tag-tab':
                if (isset($this->_json['og_tags'])) unset($this->_json['og_tags']);
                foreach ($data as $item){
                    if(empty(trim($item['name'])) || empty(trim($item['value']))) continue;
                    $this->_set(trim($item['name']), trim($item['value']), trim($item['type']));
                }
                break;
            case 'render-meta-blocks':
                return $this->drawMetaBlocks($_POST['type']);
            default:
                foreach ($data as $item){
                    $this->_set($item['name'], $item['value']);
                }
        }
        //TODO иная обработка ошибок
        $resultSave = $this->save();
        $resultSave = true; //TODO для теста
        if(!$resultSave){
            $this->_result['success'] = false;
            $this->_result['error'] = 'Не удалось сохранить настройки сайта! Попробуйте выполнить операцию позже или обратитесь в службу поддержки.';
            $this->_result['type'] = 'error';
        }
        return $this->prepare($this->_result);
    }


    /**
     * Save and encode data
     */
    private function save()
    {
        $data = empty($this->_json) ? $this->_defaults : $this->_json;
        //TODO проверки
        $r = file_put_contents($this->_file, base64_encode(json_encode($data)), LOCK_EX);
        if($r === false) return $r;
        return true;
    }

    /**
     * Load and decode data
     */
    private function load()
    {
        //TODO проверка на корректный декодинг
        $this->_json = json_decode(base64_decode(file_get_contents($this->_file)), true);
    }

    private function init()
    {
        $this->_file = Helper::normalizePath($this->_file);
        if(!file_exists($this->_file)){
            $this->save();
        }
        $this->load();
    }

    /**
     * @param $result
     * @return string
     */
    private function prepare($result)
    {
        return json_encode($result);
    }

    /**
     * @param $name
     * @param $value
     * @param bool $type
     */
    private function _set($name, $value, $type = false)
    {
        if($type){
            $this->_json[$type][$name] = $value;
        } else {
            $this->_json[$name] = $value;
        }
    }

    public function getBaseUrl()
    {
        if ($this->_baseUrl === null) {
            $this->_baseUrl = rtrim(dirname($this->getScriptUrl()), '\\/');
        }
        return $this->_baseUrl;
    }

    public function getScriptUrl()
    {
        if ($this->_scriptUrl === null) {
            $scriptFile = $this->getScriptFile();
            $scriptName = basename($scriptFile);
            if (isset($_SERVER['SCRIPT_NAME']) && basename($_SERVER['SCRIPT_NAME']) === $scriptName) {
                $this->_scriptUrl = $_SERVER['SCRIPT_NAME'];
            } elseif (isset($_SERVER['PHP_SELF']) && basename($_SERVER['PHP_SELF']) === $scriptName) {
                $this->_scriptUrl = $_SERVER['PHP_SELF'];
            } elseif (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $scriptName) {
                $this->_scriptUrl = $_SERVER['ORIG_SCRIPT_NAME'];
            } elseif (isset($_SERVER['PHP_SELF']) && ($pos = strpos($_SERVER['PHP_SELF'], '/' . $scriptName)) !== false) {
                $this->_scriptUrl = substr($_SERVER['SCRIPT_NAME'], 0, $pos) . '/' . $scriptName;
            } elseif (!empty($_SERVER['DOCUMENT_ROOT']) && strpos($scriptFile, $_SERVER['DOCUMENT_ROOT']) === 0) {
                $this->_scriptUrl = str_replace('\\', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', $scriptFile));
            } else {
                throw new \Exception('Unable to determine the entry script URL.');
            }
        }
        return $this->_scriptUrl;
    }

    public function getScriptFile()
    {
        if (isset($this->_scriptFile)) {
            return $this->_scriptFile;
        } elseif (isset($_SERVER['SCRIPT_FILENAME'])) {
            return $_SERVER['SCRIPT_FILENAME'];
        } else {
            throw new \Exception('Unable to determine the entry script file path.');
        }
    }

}