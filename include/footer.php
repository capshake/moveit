    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="<?php echo BASEDIR; ?>js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?php echo BASEDIR; ?>js/ie10-viewport-bug-workaround.js"></script>
    <script src="<?php echo BASEDIR; ?>js/main.js"></script>
    <script>
    var mainSettings = {
        'csrfToken': '<?php echo $userData->getToken(); ?>'
    }
    </script>
  </body>
</html>