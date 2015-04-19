<div class="push"></div>
</div>
<footer class="footer">
    <div class="container">
        <div class="col-md-12">
            <div class="pull-left text-muted">&copy; moveIT</div>
            <div class="pull-right"><img style="width: 92px;margin-top: 10px;" src="<?php echo BASEDIR ?>img/logo_fhd.png"></div>
        </div>
    </div>
</footer>
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="<?php echo BASEDIR; ?>js/jquery.js"></script>
<script src="<?php echo BASEDIR; ?>js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="<?php echo BASEDIR; ?>js/ie10-viewport-bug-workaround.js"></script>
<script src="<?php echo BASEDIR; ?>js/bootbox.min.js"></script>
<script src="<?php echo BASEDIR; ?>js/jqueryui.js"></script>
<script src="<?php echo BASEDIR; ?>js/context-menu.js"></script>
<script src="<?php echo BASEDIR; ?>js/moveit.js"></script>
<script src="<?php echo BASEDIR; ?>js/dropdown.js"></script>

<?php
if (isset($javascript)) {
    echo $javascript;
}
?>
<script>
    var mainSettings = {
        'csrfToken': '<?php echo $userData->getToken(); ?>',
        'isLoggedIn': <?php echo ($userData->isloggedIn()) ? 1 : 0; ?>
    }
</script>

</body>
</html>
