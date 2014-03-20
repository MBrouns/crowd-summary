<?php

/*
 * Summary controller for crowd summary
 */

class DocumentsController extends AppController {

    public $uses = array('PersonalDocument', 'Document', 'Summary', 'Sentence');

    /*
     * Overview of all documents
     */

    public function index() {

        if (isset($this->request->data['Document']['file'])) {//file has been uploaded                 
            //save document
            $this->Document->set(array(
                'title' => $this->request->data['Document']['file']['name'],
                'fulltext' => file_get_contents($this->request->data['Document']['file']['tmp_name'])
            ));

            if ($this->Document->save()) {
                $this->Session->setFlash('Document was succesfully uploaded');
                //connect user to document
                $this->PersonalDocument->saveAssociated(array(
                    'User' => array('id' => $this->Auth->user('id')),
                    'Document' => array('id' => $this->Document->id),
                ));


                //create summary
                $document = $this->Document->read(null, $this->Document->id);
                if (empty($document['Sentence'])) {//generate summary                    
                    //call java summarizer
                    $cmd = 'java -jar ' . APP . '../summarizers/Summarizer.jar ' . $this->Document->id . ' ' . APP . 'webroot\crowdsum 2>&1'; //some problems with exec in php 5.2.2+ on windows https://bugs.php.net/bug.php?id=41874 check this works on other systems
                    $lastline = exec($cmd, $output, $returnVar);

                    if (count($output) != 6) {//summarizer didn't output all 6 steps so sth is wrong
                        $this->Session->setFlash(__('Automatic summarization failed.'));
                    } else {
                        $this->Session->setFlash(__('Succesfully created automatic summary'));
                    }
                }

                return $this->redirect(array('controller' => 'documents', 'action' => 'info', $this->Document->id));
            } else {
                $this->Session->setFlash('Document could not be uploaded');
            }
        }

        //Load arguments for document list filter
        if (isset($_POST["inputTitle"])) {
            $this->set('titleFilter', mysql_real_escape_string($_POST["inputTitle"]));
        } else {
            $this->set('titleFilter', '');
        }
        if (isset($_POST["inputAuthor"])) {
            $this->set('authorFilter', mysql_real_escape_string($_POST["inputAuthor"]));
        } else {
            $this->set('authorFilter', '');
        }
        if (isset($_POST["inputContent"])) {
            $this->set('contentFilter', mysql_real_escape_string($_POST["inputContent"]));
        } else {
            $this->set('contentFilter', '');
        }

        //get all documents
        $this->set('documents', $this->Document->find('all'));
    }

    /*
     * Display summary and document to user
     * id = document id
     * mode = personal or automatic
     */

    public function summary($id, $forceMode = null) {
        $this->Document->id = $id;
        if (!$this->Document->exists()) {
            throw new NotFoundException(__('Invalid document id'));
        }

        //save summary
        if ($this->request->is('post')) {
            if ($this->request->data['Summary']['user_sentences']) {
                $ids = explode(',', $this->request->data['Summary']['user_sentences']);
                $summary = array();

                foreach ($ids as $key => $id) {
                    $summary[] = array('user_id' => $this->Auth->user('id'), 'sentence_id' => $id);
                }

                if ($this->Summary->deleteAll(array('user_id' => $this->Auth->user('id')))) {//delete old personal summary
                    if ($this->Summary->saveMany($summary)) {
                        $personalDocument = $this->PersonalDocument->find('first', array('conditions' => array('user_id' => $this->Auth->user('id'), 'document_id' => $this->Document->id)));
                        if(!empty($personalDocument)){
                            $this->PersonalDocument->id = $personalDocument['PersonalDocument']['id'];
                        }
                        if ($this->PersonalDocument->save(array('user_id' => $this->Auth->user('id'), 'document_id' => $this->Document->id))) {
                            $this->Session->setFlash(__('Your personal summary has been saved'));

                            //update generated rankings
                            $generated_sum = $this->Summary->find('all', array('conditions' => array('Summary.user_id' => 0, 'Sentence.document_id' => $this->Document->id)));
                            foreach ($generated_sum as $sentence => $data) {
                                if (in_array($data['Summary']['sentence_id'], $ids)) {
                                    $generated_sum[$sentence]['Summary']['ranking'] = $generated_sum[$sentence]['Summary']['ranking'] + 1;
                                    $ids = array_diff($ids, array($data['Summary']['sentence_id']));
                                } else {
                                    $generated_sum[$sentence]['Summary']['ranking'] = $generated_sum[$sentence]['Summary']['ranking'] - 1;
                                }
                            }

                            //add ids to new generated_sum
                            foreach ($ids as $id) {
                                // $this->Sentence->find();
                                $generated_sum[]['Summary'] = array('user_id' => 0, 'sentence_id' => $id, 'ranking' => 1);
                            }

                            //save rankings
                            if (!$this->Summary->saveMany($generated_sum)) {
                                $this->Session->setFlash(__('Ranking could not be updated'));
                            }
                        } else {
                            $this->Session->setFlash(__('Personal document could not be saved'));
                        }
                    } else {
                        $this->Session->setFlash(__('Summary could not be saved'));
                    }
                } else {
                    $this->Session->setFlash(__('Old summary could not be deleted'));
                }
            }
        }

        //display document
        $this->set('document', $this->Document->read(null, $this->Document->id));

        //see if user has personal summary
        $user = $this->Auth->user();
        $summary = $this->Summary->find('all', array('conditions' => array('user_id' => $user['id'])));
        if (!empty($summary)) {//user has summary
            $this->set('personal_summary', $summary);
            $mode = 'personal';
        } else {
            $mode = 'automatic';
        }


        //Get generated summary @TODO calculate average of all users
        $generated = $this->generate_summary($this->Document->id);
        $this->set('generated_summary', $generated);

        if(isset($forceMode)) {
            $this->set('mode', $forceMode);
        } else {
            $this->set('mode', $mode);
        }
        
    }

    /*
     * Function to generate summary from all user summaries
     * 
     * @param int $docId
     */

    private function generate_summary($docId) {
        $this->Document->id = $docId;
        if (!$this->Document->exists()) {
            throw new NotFoundException(__('Invalid document id'));
        }

        //get auto summary
        $auto_sentences = $this->Summary->find('all', array('conditions' => array('Summary.user_id' => 0, 'Sentence.document_id' => $this->Document->id, 'Summary.ranking > ' => 0),
            'orderBy' => 'Summary.ranking DESC', 'Summary.id'));

        //get all users with this docID
        $users = $this->PersonalDocument->find('all', array('conditions' => array('document_id' => $this->Document->id)));

        if (empty($users)) {//no personal summaries available
            return $auto_sentences;
        }

        $total_sentences = count($auto_sentences);
        foreach ($users as $user) {
            $summaries[] = $this->Summary->find('all', array('conditions' => array('user_id' => $user['User']['id'])));
            $total_sentences = $total_sentences + count(end($summaries));
        }

        $sum_size = ceil($total_sentences / count($users));

        return array_slice($auto_sentences, 0, $sum_size);
    }

    /*
     * Add data about the uploaded document
     * 
     * @param int id uploaded document id
     */

    public function info($id = null) {
        $this->Document->id = $id;
        if (!$this->Document->exists()) {
            throw new NotFoundException(__('Invalid document id'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Document->save($this->request->data['Info'])) {
                $this->Session->setFlash('Information was succesfully added');
                return $this->redirect(array('controller' => 'documents', 'action' => 'index'));
            } else {
                $this->Session->setFlash('Information could not be added');
            }
        }
    }

}
