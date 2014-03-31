<!-- app/View/Users/add.ctp -->
<div class="container">
    <div class="users form">
        <?php echo $this->Form->create('User'); ?>
        <fieldset>
            <legend><?php echo __('Add User'); ?></legend>
            <div class="users add">
                <?php
                echo $this->Form->input('username', array('type' => 'text'));
                echo $this->Form->input('password');
                echo $this->Form->input('passwd', array('label' => 'Re-type password'));
                ?>
            </div>
        </fieldset>
        <?php echo $this->Form->end(__('Submit')); ?>
    </div>
</div>