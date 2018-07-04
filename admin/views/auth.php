<div class="wrap">
    <div class="login-block">
        <form action="<?=\core\Url::to('auth')?>" method="post">
            <h3>CMS-Lite</h3>
            <div class="form-group">
                <input type="text" name="login" id="login" placeholder="Логин">
            </div>
            <div class="form-group">
                <input type="password" name="pwd" id="pwd" placeholder="Пароль">
            </div>
            <button type="submit" class="btn btn-admin">Войти</button>
        </form>
    </div>
</div>
