<?php
/*
 * Notes model
 */
class Note extends AppModel{
    public $belongsTo = 'Sentence';
}