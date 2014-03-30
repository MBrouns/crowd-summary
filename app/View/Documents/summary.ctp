<?php echo $this->Html->script('jquery.textHighlighter.min'); ?>
<?php echo $this->Html->script('summary'); ?>
<?php //debug($document); debug($personal_summary); debug($generated_summary);   ?>

<div class="container">
    <div class="summary-container">
        <div class="panel panel-primary">
            <div class="panel-heading">Tools</div>
            <div class="panel-body">
            	<p><?php
            	if($mode == 'personal') {
            		echo "This is your saved version of the summary. " . $this->Html->link('Open generated version.', array('controller' => 'documents', 'action' => 'summary', $document['Document']['id'] , 'automatic'));

            	} else {
            		echo "This document is automatically summarized";
                    if ($document['Document']['contributions'] > 0) {
                        echo ' and improved by ' . $document['Document']['contributions'] . ' users';
                    }
                    if (!empty($personal_summary) && !empty($notes)) {
                    	echo ". " . $this->Html->link('Open own version.', array('controller' => 'documents', 'action' => 'summary', $document['Document']['id'], 'personal'));
                    }
                }
                ?></p>
                <div class="btn-group" id="mode">
                    <button type="button" class="btn btn-default active" id="highlight-button">Highlight</button>
                    <button type="button" class="btn btn-default" id="notes-button">Notes</button>
                </div>
                <button type="button" class="btn btn-default right" id="removeAll-notes-button">Remove All Comments</button>
                <button type="button" class="btn btn-default right" id="removeAll-highlights-button">Remove All Highlights</button>

            </div>
        </div>

        <div id="summary" rel="popover" data-container="#summary" data-trigger="manual" data-toggle="popover" data-placement="right" data-html="true" data-content="<textarea rows='4' cols='35'></textarea><div class='clear'></div><input class='btn btn-primary right' id='notes-save' type='button' value='Save' /><br/> " data-original-title="Insert your comment">
        	<h1><?php echo $document['Document']['title']; ?></h1>
            <?php
            foreach ($document['Sentence'] as $sentence) {
                echo "<span id='sentence" . $sentence['id'] . "'>" . $sentence['sentence'] . "</span><br/>";
            }
            ?>	
        </div>
        <div id="pdf-summary"></div>



        <div class="panel panel-primary">
            <div class="panel-heading">Save &amp; Export</div>
            <div class="panel-body">
            	<?php
			        echo $this->Form->create('Summary');
			    ?>
		        <div class="options">
		        	<h3 class="left">Save summary</h3>    
			        <?php
			        echo $this->Form->hidden('user_sentences');
			        echo $this->Form->hidden('user_notes');
			        echo $this->Form->submit('Save', array('class' => 'btn btn-primary right', 'id' => 'save-button'));
			        ?>
			    </div>
			    <br/><br/><br/>

	            <div class="options">
					<h3 class="left">Export summary</h3>
					<?php
			        echo $this->Form->hidden('pdf_type', array('value' => 0 ));
			        echo $this->Form->hidden('pdf_notes', array('value' => 0 ));
			        echo $this->Form->hidden('html');
			        echo $this->Form->submit('Export', array('class' => 'btn btn-primary right', 'id' => 'export-button'));
			        ?>
			        <div class="btn-group right summary-options" id="div_pdf_notes">
			  			<button type="button" class="btn btn-default active">Include notes</button>
			  			<button type="button" class="btn btn-default">No notes</button>
					</div>
					<div class="btn-group right summary-options" id="div_pdf_type">
			  			<button type="button" class="btn btn-default active">Whole document with highlights</button>
			  			<button type="button" class="btn btn-default">Only Summary</button>
					</div>
				</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
		
		

    </div>
</div>

<script type="text/javascript">
    var highlights = [];
    var notes = []
<?php
// Add highlights to Javascript
if ($mode == 'personal') {
	$highlightsJS = $personal_summary;
} else {
	$highlightsJS = $generated_summary;
}

foreach ($highlightsJS as $sentence) {
    echo "highlights.push('" . addslashes($sentence['Summary']['sentence_id']) . "');\n";
}

// Add notes to javascript
foreach ($notes as $note) {
?>
obj = new Object();
obj.sentence = <?php echo $note["Note"]["sentence_id"] ?>;
obj.note = <?php echo json_encode($note["Note"]["note"]) ?>;
notes.push(obj);
<?php
}
?>
</script>
