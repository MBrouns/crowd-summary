<?php echo $this->Html->script('jquery.textHighlighter.min'); ?>
<?php echo $this->Html->script('summary'); ?>


<div class="container">
	<div class="summary-container">
		<div class="panel panel-primary">
		  <div class="panel-heading">$Document Title: summary</div>
		  <div class="panel-body">
		    <p>This document is automatically summarized and improved by 4 users. </p>
		    <div class="btn-group">
			  <button type="button" class="btn btn-default" id="highlight-button">Highlight</button>
			  <button type="button" class="btn btn-default" id="notes-button">Notes</button>
			</div> 
		  </div>
		</div>

		<div id="summary">
		<h1>Lorem Ipsum: document <?php echo $id ?></h1>

		<p>Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren '60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.</p>

		</div>
	</div>
</div>
