<?php
echo $this->Html->script('jquery.tablesorter.min');
?>

<br/>
<div class="container">
	<?php echo $this->element('documentSearchUpload'); ?>
	<hr/>
	<h2>Uploaded Documents</h2>
	<br/>

	<table id="docTable" class="tablesorter table" cellspacing="1">			
	<thead>
		<tr>
			<th>Title</th>
			<th>Author</th>
			<th># Contributors</th>
			<th>Keywords</th>
			<th>Uploaded</th>
		</tr>
	</thead>
	<tbody>
		<tr><td><a href="/documents/summary/1">A Tale of Two Cities</a></td><td>Charles Dickens</td><td>5</td><td>Literature, French Revolution</td><td>March 12th, 2014, 11:00</td></tr>
		<tr><td><a href="/documents/summary/2">1984</a></td><td>George Orwell</td><td>8</td><td>Science Fiction, NSA</td><td>March 12th, 2014, 11:00</td><tr>
	</tbody>
	</table>
</div>

<script>
$(document).ready(function()
	{
		$("#docTable").tablesorter();
	}
);
</script>
