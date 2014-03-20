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
            		echo "This is your saved version of the summary. <a href='automatic'>Open generated version</a>";
            	} else {
            		echo "This document is automatically summarized";
                    if ($document['Document']['contributions'] > 0) {
                        echo ' and improved by ' . $document['Document']['contributions'] . ' users';
                    }
                    echo ". <a href='personal'>Open your own version</a>";
                }
                ?></p>
                <div class="btn-group">
                    <button type="button" class="btn btn-default active" id="highlight-button">Highlight</button>
                    <button type="button" class="btn btn-default" id="notes-button">Notes</button>
                </div>

                <button type="button" class="btn btn-default right" id="removeAll-button">Remove All Highlights</button>                  
            </div>
        </div>

        <div id="summary">
            <?php
            foreach ($document['Sentence'] as $sentence) {
                echo "<span id='sentence" . $sentence['id'] . "'>" . $sentence['sentence'] . "</span><br/>";
            }
            ?>	
        </div>        
        <?php
        echo $this->Form->create('Summary');
        echo $this->Form->hidden('user_sentences');
        echo $this->Form->submit('Generate', array('class' => 'btn btn-primary right', 'id' => 'generate-button'));
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
