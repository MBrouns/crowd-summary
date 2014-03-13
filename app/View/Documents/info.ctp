<div class="container">
    <h2><?php echo __('Add document information'); ?></h2>
    <?php
    echo $this->Form->create('Info');
    echo $this->Form->input('title');
    echo $this->Form->input('author');
    echo $this->Form->input('keywords');  
    echo $this->Form->submit('Submit');
    ?>
</div>