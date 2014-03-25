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
                if (!$this->create_summary_java($this->Document->id)) {
                    $this->Session->setFlash(__('Automatic summarization failed.'));
                } else {
                    $this->Session->setFlash(__('Succesfully created automatic summary'));
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
     * create summary using Java library
     * 
     * @param int docId
     * @return boolean success or not
     */

    private function create_summary_java($docId) {
        $this->Document->id = $docId;
        if (!$this->Document->exists()) {
            throw new NotFoundException(__('Invalid document id'));
        }

        $document = $this->Document->read(null, $this->Document->id);
        if (empty($document['Sentence'])) {//generate summary                    
            //call java summarizer
            $cmd = 'java -jar ' . APP . '../summarizers/Summarizer.jar ' . $this->Document->id . ' ' . APP . 'webroot\crowdsum 2>&1'; //some problems with exec in php 5.2.2+ on windows https://bugs.php.net/bug.php?id=41874 check this works on other systems
            $lastline = exec($cmd, $output, $returnVar);
            debug($output);
            if (count($output) != 6) {//summarizer didn't output all 6 steps so sth is wrong
                return false;
            } else {
                return true;
            }
        }
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

                $summary = $this->sentences_to_summary($this->request->data['Summary']['user_sentences']);
                $oldIds = $this->get_old_ids($this->Document->id);

                //delete old personal summary
                if ($this->Summary->deleteAll(array('user_id' => $this->Auth->user('id')))) {

                    //save new personal summary
                    if ($this->Summary->saveMany($summary)) {
                        $personalDocument = $this->PersonalDocument->find('first', array('conditions' => array('user_id' => $this->Auth->user('id'), 'document_id' => $this->Document->id)));

                        //find out if user created summary before
                        if (!empty($personalDocument)) {
                            $this->PersonalDocument->id = $personalDocument['PersonalDocument']['id'];
                        }

                        //join user to document
                        if ($this->PersonalDocument->save(array('user_id' => $this->Auth->user('id'), 'document_id' => $this->Document->id))) {
                            $this->Session->setFlash(__('Your personal summary has been saved'));

                            //update ranking
                            if (!$this->update_ranking($ids, (isset($oldIds) ? $oldIds : null))) {
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

        //Get generated summary
        $generated = $this->generate_summary($this->Document->id);
        $this->set('generated_summary', $generated);

        if (isset($forceMode)) {
            $this->set('mode', $forceMode);
        } else {
            $this->set('mode', $mode);
        }
    }

    /*
     * Convert sentences to summary format
     */

    private function sentences_to_summary($sentences) {
        $ids = explode(',', $sentences);
        $summary = array();

        foreach ($ids as $key => $id) {
            $summary[] = array('user_id' => $this->Auth->user('id'), 'sentence_id' => $id);
        }

        return $summary;
    }

    /*
     * Get old summary ids
     */

    private function get_old_ids($docId) {
        $this->Document->id = $docId;
        if (!$this->Document->exists()) {
            throw new NotFoundException(__('Invalid document id'));
        }

        //get old personal summary sentences
        $oldSummary = $this->Summary->find('all', array('conditions' => array('Summary.user_id' => $this->Auth->user('id'), 'Sentence.document_id' => $this->Document->id)));
        if (!empty($oldSummary)) {
            $oldIds = array();
            foreach ($oldSummary as $sentence) {
                $oldIds[] = $sentence['Sentence']['id'];
            }

            return $oldIds;
        }

        return null;
    }

    /*
     * Update ranking
     * 
     * @param array ids sentence ids of new personal summary
     * @param array oldIds sentence ids of old personal summary
     * @return boolean updated ranking or not
     */

    private function update_ranking($ids, $oldIds = null) {
        //get current generated summary
        $generated_sum = $this->Summary->find('all', array('conditions' => array('Summary.user_id' => 0, 'Sentence.document_id' => $this->Document->id)));

        //user generated summary before, so find difference between ids
        if ($oldIds != null) {
            $missing = array_diff($oldIds, $ids);
            $new = array_diff($ids, $oldIds);
            $generated_sum = $this->ranking_edit($generated_sum, $new, $missing);
        } else {//no old ids
            $generated_sum = $this->initial_ranking($generated_sum, $ids);
        }

        //save generated summary
        if (!$this->Summary->saveMany($generated_sum)) {
            return false;
        }

        return true;
    }

    /*
     * Edit generated ranking
     */

    private function ranking_edit($generated_sum = array(), $new, $missing) {
        foreach ($generated_sum as $sentence => $data) {
            //all missing get -1 in ranking            
            if (in_array($data['Summary']['sentence_id'], $missing)) {
                $generated_sum[$sentence]['Summary']['ranking'] = $generated_sum[$sentence]['Summary']['ranking'] - 1;
            }

            //all new get +1 in ranking
            if (in_array($data['Summary']['sentence_id'], $new)) {
                $generated_sum[$sentence]['Summary']['ranking'] = $generated_sum[$sentence]['Summary']['ranking'] + 1;
                //remove ids that have been added to ranking
                $new = array_diff(array($data['Summary']['sentence_id']), $new);
            }
        }

        //add new ids from personal summary to generated summary
        foreach ($new as $id) {
            $generated_sum[]['Summary'] = array('user_id' => 0, 'sentence_id' => $id, 'ranking' => 1);
        }

        return $generated_sum;
    }

    /*
     * Create initial ranking
     */

    private function initial_ranking($generated_summary = array(), $ids) {
        foreach ($generated_sum as $sentence => $data) {
            //See if there is a difference between generated summary and personal summary
            if (in_array($data['Summary']['sentence_id'], $ids)) {
                $generated_sum[$sentence]['Summary']['ranking'] = $generated_sum[$sentence]['Summary']['ranking'] + 1;

                //remove ids that have been added to ranking
                $ids = array_diff($ids, array($data['Summary']['sentence_id']));
            } else {
                $generated_sum[$sentence]['Summary']['ranking'] = $generated_sum[$sentence]['Summary']['ranking'] - 1;
            }
        }

        //add new ids from personal summary to generated summary
        foreach ($ids as $id) {
            // $this->Sentence->find();
            $generated_sum[]['Summary'] = array('user_id' => 0, 'sentence_id' => $id, 'ranking' => 1);
        }

        return $generated_sum;
    }

    /*
     * Function to generate summary from all user summaries
     * 
     * @param int $docId
     */

    private function generate_summary($docId) {

        $auto_sentences = $this->get_auto_summary($docId);
        if ($auto_sentences == false) {
            return false;
        }

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
     * get automatically generated sentences
     * 
     * @param int docId
     * 
     * @return array automatically generated summary
     */

    private function get_auto_summary($docId) {
        $this->Document->id = $docId;
        if (!$this->Document->exists()) {
            throw new NotFoundException(__('Invalid document id'));
        }

        //get auto summary
        $auto_sentences = $this->Summary->find('all', array('conditions' => array('Summary.user_id' => 0, 'Sentence.document_id' => $this->Document->id, 'Summary.ranking > ' => 0),
            'orderBy' => 'Summary.ranking DESC', 'Summary.id'));

        if (empty($auto_sentences)) {//summary has not been generated yet         
            if ($this->create_summary_java($this->Document->id) != false) {
                $auto_sentences = $this->get_auto_summary($this->Document->id);
            } else {
                return false;
            }
        }

        return $auto_senteces;
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
