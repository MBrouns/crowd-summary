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
    public $actsAs = array(
        'ElasticSearchIndex.ElasticSearchIndexable' => array(
            'fields' => array('author', 'title', 'full_text', 'publication')
        ),
    );

    /*
     * Define relations
     */
    public $hasMany = array('Sentence', 'Keyword', 'PersonalDocument');

    /*
     * Index documents to elasticSearch
     */

    public function reIndex() {
        $statusString = $this->reIndexAll();
        Debugger::dump($statusString);
    }

    /*
     * search documents
     * 
     * @param string term
     */

    public function search($term) {
        $sortedIds = $this->searchAndReturnAssociationKeys($term);
        $results = $this->find('all', array(
            'conditions' => array(
                "{$this->alias}.{$this->primaryKey}" => $sortedIds
            )
        ));

        return $this->searchResultsResort($results, $sortedIds);
    }

    /*
     * Index data to elasticsearch
     */
/*
    public function afterSave($created, $options = array()) {
        //parent::afterSave($created, $options);

        $data = $this->read();
        //Debugger::log($data);
        
        $id = $data[$this->alias][$this->primaryKey];
        $success = $this->saveToIndex($id, $data);
        
    }*/

}
