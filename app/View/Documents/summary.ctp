<?php echo $this->Html->script('jquery.textHighlighter.min'); ?>
<?php echo $this->Html->script('summary'); ?>
<?php //debug($document); debug($personal_summary); debug($generated_summary);   ?>

<div class="container">
    <div class="summary-container">
        <div class="panel panel-primary">
            <div class="panel-heading"><?php echo $document['Document']['title']; ?></div>
            <div class="panel-body">
            	<p><?php
            	if($mode == 'personal') {
            		echo "This is your saved version of the summary. " . $this->Html->link('Open generated version.', array('controller' => 'documents', 'action' => 'summary', $document['Document']['id'] , 'automatic'));

            	} else {
            		echo "This document is automatically summarized";
                    if ($document['Document']['contributions'] > 0) {
                        echo ' and improved by ' . $document['Document']['contributions'] . ' users';
                    }
                    echo ". " . $this->Html->link('Open own version.', array('controller' => 'documents', 'action' => 'summary', $document['Document']['id'], 'personal'));
                }
                ?></p>
                <div class="btn-group">
                    <button type="button" class="btn btn-default active" id="highlight-button">Highlight</button>
                    <button type="button" class="btn btn-default" id="notes-button">Notes</button>
                </div>

                <button type="button" class="btn btn-default right" id="removeAll-button">Remove All Highlights</button>                  
            </div>
        </div>

        <div id="summary" rel="popover" data-container="#summary" data-trigger="manual" data-toggle="popover" data-placement="right" data-html="true" data-content="<textarea rows='4' cols='35'></textarea><div class='clear'></div><input class='btn btn-primary right' id='notes-save' type='button' value='Save' /><br/> " data-original-title="Insert your comment">
            <?php
            foreach ($document['Sentence'] as $sentence) {
                echo "<span id='sentence" . $sentence['id'] . "'>" . $sentence['sentence'] . "</span><br/>";
            }
            ?>	
        </div>        
        <?php
        echo $this->Form->create('Summary');
        echo $this->Form->hidden('user_sentences');
        echo $this->Form->hidden('user_notes');
        echo $this->Form->submit('Save', array('class' => 'btn btn-primary right', 'id' => 'generate-button'));
        ?>

        <div class="clearboth"></div>

        <h1>Summary flavour 1</h1>
        <div id="generated-summary" style="display:none"></div>

        <h1>Summary flavour 2</h1>
        <div id="user-summary"></div>

    </div>
</div>

<script type="text/javascript">
    var highlights = [];
<?php
if ($mode == 'personal') {
	$highlightsJS = $personal_summary;
} else {
	$highlightsJS = $generated_summary;
}


foreach ($highlightsJS as $sentence) {
    echo "highlights.push('" . addslashes($sentence['Summary']['sentence_id']) . "');\n";
}
?>
</script>
