<?php

/*
 * Model class for user management. Taken from cakephp cookbook
 */

App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {
    /*
     * Validate fields before updating databse
     */

    public $validate = array(
        'username' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A username is required'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A password is required'
            )
        ),
        'passwd' => array(//used for double typing password
            'equaltofield' => array(
                'rule' => array('equaltofield', 'password'),
                'message' => 'Passwords have to match',
                'on' => 'create', // Limit validation to 'create' or 'update' operations
            )
        ),
    );

    /*
     * Password hashing
     */

    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new SimplePasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                    $this->data[$this->alias]['password']
            );
        }
        return true;
    }

    /*
     * Check fields are equal
     */

    public function equaltofield($check, $otherfield) {
    //get name of field
        $fname = '';
        foreach ($check as $key => $value) {
            $fname = $key;
            break;
        }
        return $this->data[$this->name][$otherfield] === $this->data[$this->name][$fname];
    }

}
