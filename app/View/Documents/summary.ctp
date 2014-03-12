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

		<p>Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren '60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.</p>

		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. </p>


		</div>
		<button type="submit" class="btn btn-primary" id="generate-button">Generate</button>
		<div class="clearboth"></div>
		<h1>Sentence Summary</h1>
		<div id="generated-summary"></div>
		<h1>User Summary</h1>
		<div id="user-summary"></div>
	</div>
</div>
