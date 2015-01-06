<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo BASEDIR; ?>">moveit</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="<?php echo BASEDIR; ?>">Home</a></li>
            </ul>
            <?php
            if ($userData->isloggedIn()) {
                ?>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"><?php echo $userData->getFirstName() . ' ' . $userData->getLastName(); ?><span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo BASEDIR . 'user/' . $userData->getUserId(); ?>">Profil</a></li> 
                            <li><a href="<?php echo BASEDIR . 'settings'; ?>">Profil bearbeiten</a></li> 
                            <?php
                            if ($userData->isAdmin()) {
                                echo '<li><a href="' . BASEDIR . 'admin">Administration</a></li>';
                            }
                            ?>
                        </ul>
                    </li>
                    <li><a class="logout" href="#">logout</a></li>
                </ul>
            <?php } ?>
        </div><!--/.nav-collapse -->
    </div>
</div>