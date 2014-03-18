<?php
echo $this->Html->script('jquery.tablesorter.min');
echo $this->Html->script('jquery.tablesorter.widgets.min');
?>
<div class="container">    
    <h2>Your Documents</h2>
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

            <?php foreach ($data as $document) { ?>
                <tr>
                    <td><?php echo $this->Html->link($document['Document']['title'], array('controller' => 'documents', 'action' => 'summary', $document['Document']['id'] )); ?></td>
                    <td><?php echo $document['Document']['author']; ?></td>
                    <td><?php echo (isset($document['Document']['contributions']) ? $document['Document']['contributions'] : ''); ?></td>
                    <td><?php echo $document['Document']['keywords']; ?></td>
                    <td><?php echo $document['Document']['created']; ?></td></tr>
            <?php } ?>       
        </tbody>
    </table>
</div>