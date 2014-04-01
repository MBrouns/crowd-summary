<?php
/*
 * Notes model
 */
class Note extends AppModel{
    public $belongsTo = array('Sentence', 'User');
}