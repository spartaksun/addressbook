<?php
/**
 * Login form template
 * @var $form \spartaksun\addresses\form\EmployeeForm
 * @var $supervisor array
 */
?>
<h1>Login form</h1>


<?= \spartaksun\addresses\components\Html::errorSummary($form) ?>
<div class="login-form">
    <form method="post" action="" name="login-user">

        <div class="form-input">
            <label for="login[username]">Username
                <input <?= $form->attributeCssClass('username') ?>
                    type="text"
                    name="login[username]" value="<?= $form->getAttribute('username') ?>"/>
            </label>
        </div>

        <div class="form-input">
            <label for="login[password]">Password
                <input <?= $form->attributeCssClass('password') ?>
                    type="password"
                    name="login[password]" value="<?= $form->getAttribute('password') ?>"/>
            </label>
        </div>
        <p>
            <input type="submit" value="Login">
        </p>
    </form>
</div>
