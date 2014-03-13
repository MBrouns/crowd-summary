<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">WeSummarize</a>
    </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li id="menu-documents"><?php echo $this->Html->link('Documents', array('controller' => 'documents', 'action' => 'index')); ?></li>
        <li id="menu-info"><?php echo $this->Html->link('Info', array('controller' => 'info', 'action' => 'index')); ?></li>
        <li id="menu-info"><?php echo (isset($user['id']) ? $this->Html->link('Personal', array('controller' => 'users', 'action' => 'view', $user['id'])) : ''); ?></li>
      </ul>
      <form class="navbar-form navbar-right" role="form">
        <div class="form-group">
          <input type="text" placeholder="Email" class="form-control">
        </div>
        <div class="form-group">
          <input type="password" placeholder="Password" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Sign in</button>
        <button type="submit" class="btn btn-primary">Sign up</button>

      </form>
    </div>
  </div>
</div>