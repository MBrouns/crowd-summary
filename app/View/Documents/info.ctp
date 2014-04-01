<div class="container">    
    <div class="info form">
        <br/>
        <div class="well">
            <h1 id="welcome">Information</h1>
            Please provide some information about the document you just uploaded.
        </div>
        <?php
        echo $this->Form->create('Info');
        ?>
        <div class="info-fields">
            <?php
            echo $this->Form->input('title', array('class' => 'form-control'));
            echo $this->Form->input('author', array('class' => 'form-control'));
            echo $this->Form->input('publication', array('label' => ' Year of publication', 'class' => 'form-control'));
            ?>

            <?php
            echo $this->Form->submit('Submit', array('class' => 'btn btn-default'));
            ?>
        </div>
    </div>
</div>