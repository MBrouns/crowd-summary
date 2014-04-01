<?php

App::uses('AppModel', 'Model');

class Document extends AppModel {
    /*
      public $useDBConfig = 'index';

      public $actsAs = array('Elastic.Indexable');

      public $_mapping = array(
      'id' => array('type' => 'string'),
      'author' => array('type' => 'string'),
      'title' => array('type' => 'string'),
      'keywords' => array('type' => 'string'),
      'contributions' => array('type' => 'string'),
      'fulltext' => array('type' => 'string'),
      'publication' => array('type' => 'integer'),
      'created' => array('type' => 'datetime'),
      'modified' => array('type' => 'datetime')
      );

      public function elasticMapping() {
      return $this->_mapping;
      }

      public $actAs = array('Elasticsearch.Searchable' => array(

      ));

      public $useTable = 'documents'; */

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
        'ElasticSearchIndex.ElasticSearchIndexable' => array(),
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

}
