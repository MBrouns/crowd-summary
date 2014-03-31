<div class="container">
    <h2><?php echo __('Add document information'); ?></h2>
    <?php
    echo $this->Form->create('Info');
    ?>
    <?php
    echo $this->Form->input('title');
    echo $this->Form->input('author');
    echo $this->Form->input('publication', array('label' => ' Year of publication'));
    ?>

    <?php
    echo $this->Form->submit('Submit');
    ?>
</div>