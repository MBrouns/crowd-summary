<div class="row">
	<div class="col-md-6"  style="padding-right:20px; border-right: 1px solid #aaa;">
		<h2>Search for existing documents...</h2>
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