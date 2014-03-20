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
     */

    public function summary($id) {
        $this->Document->id = $id;
        if (!$this->Document->exists()) {
            throw new NotFoundException(__('Invalid document id'));
        }

        //save summary
        if ($this->request->is('post')) {
            debug($this->request);
        }

        //display document
        $this->set('document', $this->Document->read(null, $this->Document->id));

        //see if user has personal summary
        $user = $this->Auth->user();
        $summary = $this->Summary->find('all', array('conditions' => array('user_id' => $user['id'])));
        if (!empty($summary)) {//user has summary
            $this->set('personal_summary', $summary);
        }

        //Get generated summary @TODO calculate average of all users
        $generated = $this->Summary->find('all', array('conditions' => array('user_id' => 0)));
        $this->set('generated_summary', $generated);

        //temp var
        //$this->set('id', $id);
    }

    /*
     * Function to generate summary from all user summaries
     * 
     * @param int $docId
     */

    private function generate_summary($docId) {
        $this->Document->id = $id;
        if (!$this->Document->exists()) {
            throw new NotFoundException(__('Invalid document id'));
        }


        //get auto summary
        $auto_sentences = $this->Summary->find('all', array('conditions' => array('user_id' => 0)));

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

        $sum_size = $total_sentences / count($users);
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
