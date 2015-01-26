<?php
include_once '../../include/config.php';

if ($userData->isLoggedIn() && $userData->isAdmin()) {
    $headerTitle = 'Adminpanel - Gebäude';

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
            <div class="col-md-12">
                <h1>Import/Export</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7">
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
                    ?>

                    <div class="form-group">
                        <label for="csv_file">Import-Datei (csv)</label>
                        <input id="csv_file" name="csv_file" class="form-control" type="file" />
                    </div>

                    <div class="form-group">
                        <button type="submit" name="csv" class="btn btn-primary">hochladen und importieren</button>
                        
                    </div>
                </form>
<a class="btn btn-danger" href="<?php echo BASEDIR; ?>admin/importExport/export">exportiere Daten</a>

                <div class="form-group">

                    <?php
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
                    <a class="btn btn-danger" href="<?php echo BASEDIR; ?>admin/importExport/reset">Datenbank zurücksetzen</a>
                </div>


            </div>

        </div>

    </div><!-- /.container -->

    <?php
    include_once '../../include/footer.php';
} else {
    header('location: ' . BASEDIR . 'login');
}