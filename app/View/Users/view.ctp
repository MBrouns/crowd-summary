<?php
echo $this->Html->script('jquery.tablesorter.min');
echo $this->Html->script('jquery.tablesorter.widgets.min');
?>
<div class="container">
<br/>  
    <div class="well">
        <h1 id="welcome">My Summaries</h1>
        On this page you can find an overview of all summaries you have created.
    </div>
    <br/>

    <table id="docTable" class="tablesorter table" cellspacing="1">			
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Published</th>
                <th>Keywords</th>
                <th>Last edited</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($data as $document) { ?>
                <tr>
                    <td><?php echo $this->Html->link($document['Document']['title'], array('controller' => 'documents', 'action' => 'summary', $document['Document']['id'] )); ?></td>
                    <td><?php echo $document['Document']['author']; ?></td>
                    <td><?php echo $document['Document']['publication']; ?></td>
                    <td><?php echo $document['Document']['keywords']; ?></td>
                    <td><?php echo $document['Document']['modified']; ?></td></tr>
            <?php } ?>       
        </tbody>
    </table>
</div>