<?php echo $this->Html->script('jquery.textHighlighter.min'); ?>
<?php echo $this->Html->script('summary'); ?>
<?php //debug($document); ?>

<div class="container">
	<div class="summary-container">
		<div class="panel panel-primary">
		  <div class="panel-heading"><?php echo $document['Document']['title']; ?>: summary</div>
		  <div class="panel-body">
		    <p>This document is automatically summarized and improved by <?php echo $document['Document']['contributions']; ?> users. </p>
		    <div class="btn-group">
			  <button type="button" class="btn btn-default" id="highlight-button">Highlight</button>
			  <button type="button" class="btn btn-default" id="notes-button">Notes</button>
			</div> 
		  </div>
		</div>

		<div id="summary">                       
		<?php echo $document['Document']['fulltext']; ?>
		</div>
		<button type="submit" class="btn btn-primary" id="generate-button">Generate</button>
		<div class="clearboth"></div>
		<h1>Sentence Summary</h1>
		<div id="generated-summary"></div>
		<h1>User Summary</h1>
		<div id="user-summary"></div>
	</div>
</div>
