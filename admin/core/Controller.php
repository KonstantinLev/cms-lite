<?php

namespace core;

/**
 * Class Controller
 */
class Controller
{
    public $view;
    public $user;

    public function __construct()
    {
        $this->view = CmsLite::$app->view;
        $this->user = CmsLite::$app->user;
    }

    public function render($view, $params = [])
    {
        $this->view->set('cms', CmsLite::$app);
        if(!empty($params)){
            foreach ($params as $key => $value){
                $this->view->set($key, $value);
            }
        }
        $this->view->display($view);

    }

    public function redirect($url)
    {
        if(is_array($url)){
            $url = Url::goHome().'/'.implode('/', $url);
        } else {
            $url = Url::goHome().'/'.$url;
        }
        header("Location: {$url}");
        exit;
    }

    public function goHome()
    {
        header("Location: ".Url::goHome());
        exit;
    }

    public function notFound()
    {
        header("HTTP/1.0 404 Not Found");
        $this->render('not-found', [
            'title' => '404 Not Found'
        ]);
        exit;
    }

    public function actionMain()
    {
        if(!CmsLite::$app->user->isLoggedIn()){
            $this->redirect('auth');
        }
        $this->render('main');
    }

    public function actionAuth()
    {
        if(!empty($_POST)){
            $login = $_POST['login'];
            $pwd = $_POST['pwd'];
            if($this->user->login($login, $pwd)){
                $this->redirect('main');
            }
        }
        $this->render('auth');
    }

    public function actionLogout()
    {
        $this->user->logout();
        $this->redirect('auth');
    }

    public function actionAjax()
    {
        if(!CmsLite::$app->user->isLoggedIn()){
            $this->redirect('auth');
        }
        if(!empty($_FILES)){
            echo CmsLite::$app->ajax('save-ico', $_FILES['file']);
        } else {
            echo CmsLite::$app->ajax($_POST['action'], $_POST['data']);
        }

    }

}