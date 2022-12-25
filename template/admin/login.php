<?php
if (!defined('AppiEngine')) {
    header( "refresh:0; url=/");
}
?>
<div class="container">
    <div class="section">
        <div class="row">
            <div class="col s12 m8 l4">
                <div class="card" id="login-form" style="height: 352px;">
                    <div class="card-image <?php echo $this->colorBtn; ?>" style="height: 80px;">
                        <span class="card-title <?php echo $this->colorBtnText; ?>" style="background: none;">Авторизация</span>
                    </div>
                    <div class="card-content">
                        <form method="post" class="row">
                            <div class="input-field col s12">
                                <input id="login" name="login" type="text" class="validate">
                                <label for="login">Логин</label>
                            </div>
                            <div class="input-field col s12">
                                <input id="password" name="password" type="password" class="validate">
                                <label for="password">Пароль</label>
                            </div>
                            <div class="input-field col s12">
                                <button class="right btn waves-effect waves-light <?php echo $this->colorBtn . ' ' . $this->colorBtnText ?> lighten-1 z-depth-0" type="submit" name="admin-login">
                                    Войти
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
