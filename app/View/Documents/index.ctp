<?php
//echo $this->Html->script('jquery.tablesorter.min');
//echo $this->Html->script('jquery.tablesorter.widgets.min');
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
				<th>Uploaded</th>
			</tr>
		</thead>
		<tbody>

			<?php foreach ($documents as $document) { ?>
				<tr>
					<td><?php echo $this->Html->link($document['Document']['title'], array('controller' => 'documents', 'action' => 'summary', $document['Document']['id'])); ?></td>
					<td><?php echo $document['Document']['author']; ?></td>
					<td><?php echo (isset($document['Document']['contributions']) ? $document['Document']['contributions'] : '0'); ?></td>
				<!--	<td>
						<?php /*
						$keywords = '';
						foreach ($document['Keyword'] as $keyword) {
							$keywords .= $keyword['keyword'] . ', ';
						}
						echo substr($keywords, 0, -1); */
						?>                        
					</td> -->
					<td><?php echo $document['Document']['created']; ?></td></tr>
			<?php } ?>       
		</tbody>
	</table>
</div>