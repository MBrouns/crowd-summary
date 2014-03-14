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

            <?php foreach ($documents as $document) { ?>
                <tr>
                    <td><?php echo $this->Html->link($document['Document']['title'], array('controller' => 'documents', 'action' => 'summary', $document['Document']['id'])); ?></td>
                    <td><?php echo $document['Document']['author']; ?></td>
                    <td><?php echo (isset($document['Document']['contributions']) ? $document['Document']['contributions'] : ''); ?></td>
                    <td>
                        <?php
                        $keywords = '';
                        foreach ($document['Keyword'] as $keyword) {
                            $keywords .= $keyword['keyword'] . ', ';
                        }
                        echo substr($keywords, 0, -1);
                        ?>                        
                    </td>
                    <td><?php echo $document['Document']['created']; ?></td></tr>
            <?php } ?>       
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(document).ready($(function() {

        var table = $('#docTable').tablesorter({
            widgets: ["filter"],
            widgetOptions: {
                // use the filter_external option OR use bindSearch function (below)
                // to bind external filters.
                // filter_external : '.search',

                filter_columnFilters: false,
                filter_saveFilters: true,
                filter_reset: '.reset'
            }
        });

        // Handle already entered query
        /*var filters = new Array();
         
         var t = <?php echo $titleFilter; ?>;
         var a = <?php echo $authorFilter; ?>;
         var c = <?php echo $contentFilter; ?>;
         
         if(t) { filters[0] = t; }
         if(a) { filters[1] = a; }
         if(c) { filters[3] = c; }
         // using "table.hasFilters" here to make sure we aren't targeting a sticky header
         $.tablesorter.setFilters( $('table.hasFilters'), filters, true );  
         */
        // Target the $('.search') input using built in functioning
        // this binds to the search using "search" and "keyup"
        // Allows using filter_liveSearch or delayed search &
        // pressing escape to cancel the search
        $.tablesorter.filter.bindSearch(table, $('.search'));

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
