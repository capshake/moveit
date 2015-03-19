<?php
include_once '../../include/config.php';

if ($userData->isLoggedIn() && $userData->isAdmin()) {

    include_once '../include/classes/importExport.class.php';
    $importExportData = new importExport();

    if (isset($_GET['export'])) {
        $importExportData->export();
    }

    include_once '../../include/header.php';
    include_once '../include/menu.php';
    ?>

    <div class="container">
        <div class="row">
            <div class="col-md-7 col-md-offset-3">
                <h2>Import/Export</h2>
            </div>
        </div>
        <div class="row">

            <div class="col-md-7 col-md-offset-3">
                <div class="well">
                    <form method="POST" action="<?php echo BASEDIR; ?>admin/importExport" role="form" enctype="multipart/form-data">

                        <?php
                        if (isset($_POST['csv'])) {
                            $upload = json_decode($importExportData->uploadFile($_POST, $_FILES));
                            if ($upload->status == 'error') {
                                ?>
                                <div class="alert alert-danger"><?php echo $upload->msg; ?></div>
                                <?php
                            }
                            if ($upload->status == 'success') {
                                ?>
                                <div class="alert alert-success"><?php echo $upload->msg; ?></div>
                                <?php
                            }
                        }

                        if (isset($_GET['reset'])) {
                            $reset = json_decode($importExportData->reset());
                            if ($reset->status == 'error') {
                                ?>
                                <div class="alert alert-danger"><?php echo $reset->msg; ?></div>
                                <?php
                            }
                            if ($reset->status == 'success') {
                                ?>
                                <div class="alert alert-success"><?php echo $reset->msg; ?></div>
                                <?php
                            }
                        }
                        ?>


                        <div class="form-group">
                            <label for="csv_file">Import-Datei (csv)</label>
                            <input id="csv_file" name="csv_file" class="form-control" type="file" accept=".csv" />
                        </div>

                        <div class="form-group">
                            <button type="submit" name="csv" class="btn btn-danger">Hochladen und importieren</button>
                            <a class="btn btn-danger reset-database" href="<?php echo BASEDIR; ?>admin/importExport/reset">Datenbank zurücksetzen</a>
                        </div>
                    </form>

                    <a class="btn btn-default" href="<?php echo BASEDIR; ?>admin/importExport/export">Daten exportieren</a>

                </div>
            </div>
        </div>

    </div><!-- /.container -->

    <?php
    include_once '../../include/footer.php';
} else {
    header('location: ' . BASEDIR . 'login');
}
