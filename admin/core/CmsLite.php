<?php

namespace core;
use core\helpers\ICOGenerator;
use Loader;

/**
 * Class CmsLite
 * @property View view
 * @property Router router
 * @property Request request
 * @property User user
 * @property array json
 * @property string robotsTxt
 * @package cms
 */
class CmsLite
{
    /**
     * Configure file
     * @var string
     */
    private $_file = 'core/config/config.data';

    /**
     * @var
     */
    private $_robotsTxtFile;

    /**
     * @var mixed
     */
    private $_json;

    /**
     * @var array
     */
    private $_defaults = [
        'access' => [
            [
                'login' => 'test',
                'pwd' => '827ccb0eea8a706c4c34a16891f84e7b'
            ]
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
        'success' => '',
        'message' => '',
        'type' => ''
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
     * @var Request
     */
    private $_request;

    /**
     * @var View
     */
    private $_view;

    /**
     * @var CmsLite
     */
    public static $app;

    /**
     * CmsLite constructor.
     */
    public function __construct()
    {
        $this->_file = Helper::normalizePath(Loader::$rootDir . DIRECTORY_SEPARATOR . $this->_file);
        if(!file_exists($this->_file)){
            $this->save();
        }
        $this->load();
        $this->_user = new User();
        $this->_view = new View();
        $this->_request = new Request();
        $this->_router = new Router();
        $this->_robotsTxtFile = Loader::$rootDir . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR .'robots.txt';
        static::$app = $this;
    }

    /**
     * @return View
     */
    public function getView()
    {
        return $this->_view;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->_router;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * @return mixed
     */
    public function getJson()
    {
        return $this->_json;
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
    public function getTitle()
    {
        return $this->get('title');
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

    /**
     * @return mixed|string
     */
    public function getMetrics()
    {
        return $this->get('metrics');
    }

    /**
     * @return bool|string
     */
    public function getRobotsTxt()
    {
        return is_file($this->_robotsTxtFile) ? file_get_contents($this->_robotsTxtFile) : '';
    }

    /**
     * @param $data
     */
    public function setRobotsTxt($data)
    {
        file_put_contents($this->_robotsTxtFile, $data, LOCK_EX);
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
     * @param $name
     * @param $value
     * @throws \Exception
     */
    public function __set($name, $value)
    {
        $setter = 'set' . ucfirst($name);
        if (method_exists($this, $setter)) {
            call_user_func([$this, $setter], $value);
        } elseif (method_exists($this, 'get' . ucfirst($name))) {
            throw new \Exception('Setting read-only property: ' . get_class($this) . '::' . $name);
        } else {
            throw new \Exception('Setting unknown property: ' . get_class($this) . '::' . $name);
        }
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function run()
    {
        $action = $this->router->getRoute();
        $action = 'action'.ucfirst($action);
        $controller = new Controller();
        if(method_exists($controller, $action)){
            $controller->$action();
        } else {
            $this->notFound();
        }
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
            <a rel="nofollow" class="meta-times" onclick="removeMetaBlock(this);"><i class="fal fa-times-circle"></i></a>
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
            <a rel="nofollow" class="meta-times" onclick="removeMetaBlock(this);"><i class="fal fa-times-circle"></i></a>
        </div>
    </div>
</div>';
            }
            return $result;
        }
        return $default;
    }

    //TODO скорее всего можно объединить og и обычную генерацию
    public function generateMetaTags()
    {
        $tags = $this->get('meta_tags');
        $result = '';
        //TODO проверить и убрать тримы и прочее
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
     * @throws \Exception
     */
    public function ajax($action, $data)
    {
        switch ($action){
            case 'save-main-tab':
                $result = $this->saveDataFromAjax($data);
                if($result == 'TRUE'){
                    $this->_result['success'] = true;
                    $this->_result['message'] = 'Данные успешно сохранены!';
                    $this->_result['type'] = 'success';
                } elseif ($result == 'FALSE'){
                    $this->_result['success'] = false;
                    $this->_result['message'] = 'Не удалось сохранить данные! Попробуйте выполнить операцию еще раз или обратитесь в службу поддержки.';
                    $this->_result['type'] = 'error';
                } else {
                    $this->_result['success'] = false;
                    $this->_result['message'] = 'Нет данных для сохранения!';
                    $this->_result['type'] = 'warn';
                }
                return $this->prepare($this->_result);
            case 'render-meta-blocks':
                return $this->drawMetaBlocks($_POST['type']);
            case 'save-tags':
                $result = $this->saveDataFromAjax($data);
                $tagName = $data['type'] == 'meta_tags' ? 'Мета-теги' : 'Open Graph теги';
                if($result == 'TRUE'){
                    $this->_result['success'] = true;
                    $this->_result['message'] = $tagName.' успешно сохранены!';
                    $this->_result['type'] = 'success';
                } elseif ($result == 'FALSE'){
                    $this->_result['success'] = false;
                    $this->_result['message'] = 'Не удалось сохранить '.$tagName.'! Попробуйте выполнить операцию еще раз или обратитесь в службу поддержки.';
                    $this->_result['type'] = 'error';
                } else {
                    $this->_result['success'] = false;
                    $this->_result['message'] = 'Нет данных для сохранения!';
                    $this->_result['type'] = 'warn';
                }
                return $this->prepare($this->_result);
            case 'save-metrics':
                $result = $this->saveDataFromAjax($data);
                if($result == 'TRUE'){
                    $this->_result['success'] = true;
                    $this->_result['message'] = 'Код метрики успешно сохранен!';
                    $this->_result['type'] = 'success';
                } elseif ($result == 'FALSE'){
                    $this->_result['success'] = false;
                    $this->_result['message'] = 'Не удалось сохранить код метрики! Попробуйте выполнить операцию еще раз или обратитесь в службу поддержки.';
                    $this->_result['type'] = 'error';
                } else {
                    $this->_result['success'] = false;
                    $this->_result['message'] = 'Нет данных для сохранения!';
                    $this->_result['type'] = 'warn';
                }
                return $this->prepare($this->_result);
            case 'save-robots':
                $this->robotsTxt = $data['robots'];
                $this->_result['success'] = true;
                $this->_result['message'] = 'Файл Robots.txt успешно сохранен!';
                $this->_result['type'] = 'success';
                return $this->prepare($this->_result);
            case 'save-ico':
                $fileName = basename($data['name']);
                if(!empty($fileName)){
                    $source = Helper::normalizePath(Loader::$rootDir . DIRECTORY_SEPARATOR . 'img'. DIRECTORY_SEPARATOR.$fileName);
                    $destination = Helper::normalizePath(Loader::$rootDir . DIRECTORY_SEPARATOR . 'img'. DIRECTORY_SEPARATOR .'example.ico');
                    move_uploaded_file($data['tmp_name'], $source);
                    $ico_lib = new ICOGenerator($source, [[32, 32]]);
                    $ico_lib->saveICO($destination);
                    unlink($source);
                    $this->_result['success'] = true;
                    $this->_result['message'] = 'Favicon успешно установлен!';
                    $this->_result['type'] = 'success';
                    $this->_result['link-ico'] = Url::to('img/example.ico');
                } else {
                    $this->_result['success'] = false;
                    $this->_result['message'] = 'Нет данных для сохранения!';
                    $this->_result['type'] = 'warn';
                }
                return $this->prepare($this->_result);
            default:
                //TODO
        }
    }

    /**
     * @param $data
     * @return string
     */
    private function saveDataFromAjax($data)
    {
        $type = $data['type'] !== '' ? $data['type'] : false;
        if($type){
            if (isset($this->_json[$type])) unset($this->_json[$type]);
        }
        if(is_array($data) && !empty($data)){
            foreach ($data as $item){
                if(!is_array($item)) continue;
                $name = strtolower(trim($item['name']));
                $value = trim($item['value']);
                if(empty($name) || empty($value)) continue;
                $this->_set($name, $value, $type);
            }
            return $this->save() ? 'TRUE' : 'FALSE';
        }
        return 'EMPTY';
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

    /**
     * Go to page 404
     */
    private function notFound()
    {
        $controller = new Controller();
        $controller->notFound();
    }
}