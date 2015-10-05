<?php
/**
 * Site layout template
 * @var $this \spartaksun\addresses\Application
 */
$session = \spartaksun\addresses\components\Session::getInstance();
$auth = new \spartaksun\addresses\components\UserAuth();
?>
<!DOCTYPE html>
<html lang="en-EN">
<head>
    <meta charset="UTF-8"/>
    <title>Address book</title>
    <link href="/css/style.css" rel="stylesheet">
    <script src="/js/main.js"></script>
</head>
<body>
<div class="wrapper">
    <div class="container">

        <div class="nav">
            <ul class="navigation">
                <li class="active"><a href="/">Main page</a></li>
                <?php if ($auth->isAuthenticate()): ?>
                    <li>
                        <a href="/admin">Control panel</a>
                    </li>
                    <li>
                        <a href="/logout">Logout</a>
                        (<?= $auth->getUserName() ?>)
                    </li>
                <?php else: ?>
                    <li><a href="/login">Login</a></li>
                <?php endif ?>
            </ul>
        </div>
    </div>


    <?php if ($session->hasFlash()): ?>
        <div class="message">
            <?= $session->getFlash() ?>
        </div>
    <?php endif ?>
    <div class="container">{{content}}</div>
</div>


</body>
</html>
