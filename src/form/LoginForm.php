<?php

namespace spartaksun\addresses\form;


use spartaksun\addresses\components\Db;
use spartaksun\addresses\components\UserAuth;

/**
 * Administrator log-in form
 * @package spartaksun\addresses\form
 */
class LoginForm extends Form
{
    /**
     * @inheritdoc
     */
    protected function validators()
    {
        $obj = $this;
        return array(
            'username' => function($attribute) use ($obj) {
                $obj->filterTrim($attribute);
                $obj->validateNotEmpty($attribute);
                $obj->filterLowerCase($attribute);
            },
            'password' => function($attribute) use ($obj) {
                $obj->filterTrim($attribute);
                $obj->validateNotEmpty($attribute);
            },
        );
    }

    /**
     * Check email and password
     * Login user
     * @return bool
     */
    public function login()
    {
        $username = $this->getAttribute('username');

        $this->_user = $this->db->select(Db::TABLE_USER, array(
            'username' => $username,
            'password' => UserAuth::hash(
                $this->getAttribute('password')
            ),
        ));

        if(!empty($this->_user)) {

            $identity = new UserAuth();
            $identity->login($username);

            return true;
        } else {
            $this->addError('password','Incorrect username or password');
        }

        return false;
    }
}
