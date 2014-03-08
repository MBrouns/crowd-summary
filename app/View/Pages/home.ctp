<?php

if (!Configure::read('debug')):
	throw new NotFoundException();
endif;
App::uses('Debugger', 'Utility');
?>

<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
  <div class="container">
    <h1>Welcome to WeSummarize</h1>
    <p>WeSummarize allows you to automatically generate summaries for documents and collaboratively improve them.</p>
    <p><a href="/info" class="btn btn-primary btn-lg" role="button">Learn more &raquo;</a></p>
  </div>
</div>

<div class="container">
  <!-- Example row of columns -->
  <div class="row">
    <div class="col-md-4">
      <h2>Cloud based</h2>
      <p>WeSummarize stores your summaries safely in the cloud, making sure you will always have access and never lose it.</p>
      <p><a class="btn btn-default" href="/info#cloud" role="button">View details &raquo;</a></p>
    </div>
    <div class="col-md-4">
      <h2>Collaborative Improvement</h2>
      <p>WeSummarize enables you to work together with your friends or colleagues to get the best summary for your needs.</p>
      <p><a class="btn btn-default" href="info#collaborative" role="button">View details &raquo;</a></p>
    </div>
    <div class="col-md-4">
      <h2>Get rewards</h2>
      <p>Earn points by getting your improvements accepted by as many people as possible. Use these points to get rewards!</p>
      <p><a class="btn btn-default" href="/info#rewards" role="button">View details &raquo;</a></p>
    </div>
    
  </div>
  <hr>
  <?php echo $this->element('documentSearchUpload'); ?>
  <hr>
  <?php echo $this->element('footer'); ?>
  
    </div> <!-- /container -->