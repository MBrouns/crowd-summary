<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.ico">

    <title>Welcome to WeSummarize <?php echo $title_for_layout; ?></title>

    <?php

    echo $this->Html->meta('icon');

    echo $this->Html->css('bootstrap.min');
    echo $this->Html->css('jumbotron');
    echo $this->Html->css('styles');
    ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
    <?php echo $this->Html->script('bootstrap.min'); ?>
    <?php echo $this->Html->script('scripts'); ?>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <?php echo $this->element('topbar'); ?>

    <?php echo $this->Session->flash(); ?>

	<?php echo $this->fetch('content'); ?>




    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
  </body>
</html>
