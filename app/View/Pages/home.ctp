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
        <p><a class="btn btn-primary btn-lg" role="button">Learn more &raquo;</a></p>
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4">
          <h2>Cloud based</h2>
          <p>WeSummarize stores your summaries safely in the cloud, making sure you will always have access and never lose it.</p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
        </div>
        <div class="col-md-4">
          <h2>Collaborative Improvement</h2>
          <p>WeSummarize enables you to work together with your friends or colleagues to get the best summary for your needs.</p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
       </div>
        <div class="col-md-4">
          <h2>Get rewards</h2>
          <p>Earn points by getting your improvements accepted by as many people as possible. Use these points to get rewards!</p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
        </div>
			
		</div>
		<hr>
		<div class="row">
			<div class="col-md-6"  style="padding-right:20px; border-right: 1px solid #aaa;">
				<h2>Search for existing summaries...</h2>
				<form class="form-horizontal" role="form">
				  <div class="form-group">
					<label for="inputAuthor" class="col-sm-2 control-label">Author</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputAuthor" placeholder="F. Sanger, S. Nicklen, and A. R. Coulson">
					</div>
				  </div>
				  <div class="form-group">
					<label for="inputTitle" class="col-sm-2 control-label">Title</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputTitle" placeholder="DNA sequencing with chain-terminating inhibitors">
					</div>
				  </div>
				  <div class="form-group">
					<label for="inputContent" class="col-sm-2 control-label">Content</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputContent" placeholder="DNA, Biochemistry">
					</div>
				  </div>
				  <div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
					  <button type="submit" class="btn btn-default">Search</button>
					</div>
				  </div>
				</form>
			</div>
			<div  class="col-md-6">
				<h2>...Or upload your own!</h2>
				<form role="form">
				  <div class="form-group">
					<input type="file" id="uploadSummary">
					<p class="help-block">Choose an existing document on your hard disk.</p>
				  </div>
				  <button type="submit" class="btn btn-default">Submit</button>
				</form>
			</div>
		</div>
      <hr>
      <?php echo $this->element('footer'); ?>
      
    </div> <!-- /container -->