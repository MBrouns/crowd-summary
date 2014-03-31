<div class="container">
    <div class="users form">
        <?php echo $this->Session->flash('auth'); ?>
        <?php echo $this->Form->create('User'); ?>
        <fieldset>
            <legend>
                <?php echo __('Please enter your username and password'); ?>
            </legend>
            <div class ="users inputs">
                <?php
                echo $this->Form->input('username', array('type' => 'text'));
                echo $this->Form->input('password');
                ?>
            
        </fieldset>
        <?php echo $this->Form->end(__('Login')); ?>
        </div>
    </div>
</div>