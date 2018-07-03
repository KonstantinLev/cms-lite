<?php

namespace core;

use Exception;

require_once 'Helper.php';

class View
{
    /**
     * @var string
     */
    private $tplDir = 'views';

    /**
     * @var array
     */
    private $data = [];

    /**
     * Bind variables for Views
     * @param $name
     * @param $value
     */
    public function set($name, $value)
    {
        $this->data[$name] = $value;
    }


    /**
     * @param $view
     * @throws Exception
     * @throws \Throwable
     */
    public function display($view)
    {
        $content = $this->render($view);
        echo $this->renderLayout($content);
    }

    /**
     * @param $content
     * @return null
     * @throws Exception
     * @throws \Throwable
     */
    public function renderLayout($content)
    {
        return $this->render('layout', ['content' => $content]);
    }

    /**
     * @param $view
     * @param array $params
     * @return null|string
     * @throws Exception
     * @throws \Throwable
     */
    public function render($view, $params = [])
    {
        $view .= '.php';
        $fileName = Helper::normalizePath($this->tplDir.DIRECTORY_SEPARATOR.ltrim($view, '\\/'));
        if (!is_file($fileName)){
            throw new Exception("Неизвестный файл шаблона: {$fileName}");
        }
        $data = array_merge($this->data, $params);
        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
        extract($data, EXTR_OVERWRITE);
        try {
            require ($fileName);
            return ob_get_clean();
        } catch (\Exception $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        } catch (\Throwable $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        }
    }
}