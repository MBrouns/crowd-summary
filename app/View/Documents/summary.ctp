<?php echo $this->Html->script('jquery.textHighlighter.min'); ?>
<?php echo $this->Html->script('summary'); ?>
<?php //debug($document);  ?>

<div class="container">
    <div class="summary-container">
        <div class="panel panel-primary">
            <div class="panel-heading"><?php echo $document['Document']['title']; ?></div>
            <div class="panel-body">
                <p>This document is automatically summarized<?php 
                if ($document['Document']['contributions'] > 0) {
                	echo ' and improved by '. $document['Document']['contributions'] .' users';
                }
                ?>.</p>
                <div class="btn-group">
                    <button type="button" class="btn btn-default active" id="highlight-button">Highlight</button>
                    <button type="button" class="btn btn-default" id="notes-button">Notes</button>
                </div> 
            </div>
        </div>

        <div id="summary">
            <?php echo htmlentities($document['Document']['fulltext'], ENT_QUOTES); ?>
        </div>
        <button type="submit" class="btn btn-primary" id="generate-button">Generate</button>
        <div class="clearboth"></div>
        <h1>Sentence Summary</h1>
        <div id="generated-summary">
            
        </div>
        <h1>User Summary</h1>
        <div id="user-summary"></div>
    </div>
</div>

<script type="text/javascript">
var generated = [];
<?php
    foreach ($document['Sentence'] as $sentence) {
        echo "generated.push('".addslashes($sentence['sentence']) ."');\n";
    }
?>
</script>
