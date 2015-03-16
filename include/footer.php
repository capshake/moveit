        <div class="push"></div>
    </div>
    <footer class="footer">
      <div class="container">
        <p class="text-muted">&copy; moveIT <img alt="fhd" src="<?php echo BASEDIR?>img/logo_fhd.png" class="col-md-offset-10"></p>

      </div>
    </footer>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="<?php echo BASEDIR; ?>js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?php echo BASEDIR; ?>js/ie10-viewport-bug-workaround.js"></script>
    <script src="<?php echo BASEDIR; ?>js/bootbox.min.js"></script>
    <script src="<?php echo BASEDIR; ?>js/jqueryui.js"></script>
    <script src="<?php echo BASEDIR; ?>js/context-menu.js"></script>
    <script src="<?php echo BASEDIR; ?>js/bootstrap-select.min.js"></script>
    <script src="<?php echo BASEDIR; ?>js/bootstrap-select.defaults-de_DE.min.js"></script>
    <script src="<?php echo BASEDIR; ?>js/moveit.js"></script>
    <script src="<?php echo BASEDIR; ?>js/main.js"></script>


    <?php
    if (isset($javascript)) {
        echo $javascript;
    }
    ?>
    <script>
        var mainSettings = {
            'csrfToken': '<?php echo $userData->getToken(); ?>'
        }

        $('.selectpicker').selectpicker();
    </script>

  </body>
</html>
