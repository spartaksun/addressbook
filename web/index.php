<?php

$config = require("../config.php");
require __DIR__ . "/../autoload.php";

$app = new \spartaksun\addresses\Application($config['app']);
\spartaksun\addresses\components\Db::configure($config['db']);

## Routes

/* Main page */
$app->route('/', function () use ($app) {
    $tree = new \spartaksun\addresses\components\EmployeeTree();
    $app->render('index', array(
        'tree' => $tree,
    ));
});


/* Create employee form */
$app->route('/admin/create', function ($id) use ($app) {
    $app->checkAuthenticate();
    $form = new \spartaksun\addresses\form\EmployeeForm();
    $form->setAttribute('supervisor_id', $id);

    if (!empty($_POST['employee'])) { /* received form */
        if ($form->load($_POST['employee'])) {
            if ($form->save()) {
                \spartaksun\addresses\components\Session::getInstance()->setFlash(
                    'Employee successfully added!'
                );

                $app->redirect(\spartaksun\addresses\components\Html::createUrl('/admin'));
            }
        }
    }

    $app->render('create_form', array(
        'form' => $form,
    ));
});


/* Delete employee */
$app->route('/admin/delete', function($id) use ($app){
    $app->checkAuthenticate();
    $table = \spartaksun\addresses\components\Db::TABLE_EMPLOYEE;
    $db = \spartaksun\addresses\components\Db::getInstance();

    $children = $db->selectAll($table, array('supervisor_id' => $id), array('id'));
    $session = \spartaksun\addresses\components\Session::getInstance();

    if(empty($children)) {
        $deleted = $db->deleteById($table, $id);
        if($deleted) {
            $session->setFlash('Row successfully deleted.');
        } else {
            $session->setFlash('Error during deleting.');
        }
    } else {
        $session->setFlash('You can not delete row with nested entries.');
    }

    $app->redirect('/admin');
});


/* Login form */
$app->route('/login', function () use ($app) {

    $form = new \spartaksun\addresses\form\LoginForm();
    if(!empty($_POST['login'])) {
        if($form->load($_POST['login']) && $form->login()) {
            $app->redirect('/admin');
        }
    }

    $app->render('login_form', array(
        'form' => $form,
    ));
});


/* Action logout */
$app->route('/logout', function () use ($app) {
    $auth = new \spartaksun\addresses\components\UserAuth();
    $auth->logout();

    $app->redirect('/login');
});


/* Control panel index page */
$app->route('/admin', function() use ($app) {
    $app->checkAuthenticate();
    $tree = new \spartaksun\addresses\components\EmployeeTree();

    $app->render('admin', array(
        'tree' => $tree,
    ));
});



## Run application
try {
    $app->run();
} catch (\spartaksun\addresses\AddressBookException $e) {

    $app->render('error', array(
        'message' => $e->getMessage(),
    ));
}

