<?php
echo $this->Html->script('jquery.tablesorter.min');
echo $this->Html->script('jquery.tablesorter.widgets.min');
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
$(document).ready($(function() {

  var table = $('#docTable').tablesorter({
    widgets: ["filter"],
    widgetOptions : {
      // use the filter_external option OR use bindSearch function (below)
      // to bind external filters.
      // filter_external : '.search',

      filter_columnFilters: false,
      filter_saveFilters : true,
      filter_reset: '.reset'
    }
  });

  // Target the $('.search') input using built in functioning
  // this binds to the search using "search" and "keyup"
  // Allows using filter_liveSearch or delayed search &
  // pressing escape to cancel the search
  $.tablesorter.filter.bindSearch( table, $('.search') );

  // Basic search binding, alternate to the above
  // bind to search - pressing enter and clicking on "x" to clear (Webkit)
  // keyup allows dynamic searching
  /*
  $(".search").bind('search keyup', function (e) {
    $('table').trigger('search', [ [this.value] ]);
  });
  */


}));
</script>
