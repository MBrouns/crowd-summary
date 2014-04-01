<div class="row">
    <div class="col-md-6"  style="padding-right:20px; border-right: 1px solid #aaa;">
        <h2>Search for existing documents...</h2>

        <div class="form-group">
            <?php echo $this->Form->create(null, array('url' => array('controller' => 'documents', 'action' => 'index'))); ?>
            <div class="col-sm-10">
                <?php echo $this->Form->input('Elastic.query', array('label' => '', 'class' => 'form-control search')); ?>
            </div>
      
            <div class="col-sm-offset-2 col-sm-10 search-button-div">
                <!-- <button type="submit" class="btn btn-default">Search</button> -->
                <?php echo $this->Form->submit('Search', array('class' => 'btn btn-default right')); ?>
                <?php echo $this->Form->end();?>

            </div>
        </div>
        </form>
    </div>
    <div  class="col-md-6">
        <h2>...Or upload your own!</h2>

        <?php echo $this->Form->create(null, array('type' => 'file', 'url' => array('controller' => 'documents', 'action' => 'index'))); ?>       

        <div class="form-group">
            <?php echo $this->Form->file('Document.file'); ?>
            <p class="help-block">Choose an existing document on your hard disk.</p>
        </div>
        <?php echo $this->Form->submit('Submit'); ?>
        <?php echo $this->Form->end();?>

    </div>
</div>