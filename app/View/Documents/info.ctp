<div class="container">
    <h2><?php echo __('Add document information'); ?></h2>
    <div class="users form">
        <?php
        echo $this->Form->create('Info');
        ?>
        <div class="users add">
            <?php
            echo $this->Form->input('title');
            echo $this->Form->input('author');
            echo $this->Form->input('publication', array('label' => ' Year of publication'));
            ?>
        </div>
        <?php
        echo $this->Form->submit('Submit');
        ?>
    </div>
</div>