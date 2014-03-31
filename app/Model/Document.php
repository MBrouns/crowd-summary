<?php

App::uses('AppModel', 'Model');

class Document extends AppModel {
        
    
    public $validate = array(
        'file' => array(
            'rule' => array(
                'extension',
                array('txt')
            ),
            'message' => 'You can only upload txt files'
        )
    );

    /*
     * Define relations
     */
    public $hasMany = array('Sentence', 'Keyword',  'PersonalDocument');

}
