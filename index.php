<?php
include_once 'include/config.php';


if (!$userData->isLoggedIn()) {
    header('location: ' . BASEDIR . 'login');
} else {

    include_once 'include/header.php';

    include_once 'include/menu.php';
    ?>


    <div class="container">
        <div class="row">
            <!-- Altbau -->
            <div class="col-md-3 altbau">
                <div class="row">
                    <h3>Altbau</h3>
                </div>

                <div class="row">
                    <label for="trakt">Trakt</label>
                    <div class="form-group">
                        <select id="trakt_alt" class="selectpicker" data-live-search="true">
                            <option value="1">H-Trakt</option>
                            <option value="2">S-Trakt</option>
                            <option value="3">L-Trakt</option>
                        </select>
                    </div> <!-- /.form-group -->
                </div> <!-- /.row -->

                <div class="row">
                    <label for="etage">Etage</label>
                    <div class="form-group">
                        <select id="etage_alt" class="selectpicker" data-live-search="true">
                            <option value="-2">2. UG</option>
                            <option value="-1">1. UG</option>
                        </select>
                    </div> <!-- /.form-group -->
                </div> <!-- /.row -->

                <div class="row">
                    <label for="trakt">Raum</label>
                    <div class="form-group">
                        <select id="raum_alt" class="selectpicker" data-live-search="true">
                            <option value="1">H1.11</option>
                        </select>
                    </div> <!-- /.form-group -->
                </div> <!-- /.row -->

                <div class="row">
                    <ul class="altbau list-group">
                        <li class="list-group-item">Tisch</li>
                        <li class="list-group-item">Stuhl</li>
                    </ul>
                </div>
            </div>

            <!-- Neubau -->
            <div class="col-md-6 neubau">
                <div class="row">
                    <h3>Neubau</h3>
                </div>

                <div class="row">
                    <label for="trakt_neu">Trakt/Gebäude</label>
                    <select id="trakt_neu" class="selectpicker">
                        <option value="1">H-Trakt</option>
                    </select>

                    <label for="etage_neu">Etage</label>
                    <select id="etage_neu" class="selectpicker">
                        <option value="-2">2. UG</option>
                        <option value="-1">1. UG</option>
                    </select>

                    <label for="raum_neu">Raum</label>
                    <select id="raum_neu" class="selectpicker">
                        <option value="1">H1.11</option>
                    </select>
                </div>

                <div class="row">
                    Karte
                </div>
            </div>

            <!-- Persönlich -->
            <div class="col-md-3 persoenlich">
                <div class="row">
                    <div class="row">
                        <h3>Lager, Wunschliste, Müll</h3>
                    </div>
                    <div class="row">
                        <ul class="pers list-group">
                            <li class="list-group-item">Tisch</li>
                            <li class="list-group-item">Stuhl</li>
                        </ul>
                    </div>
                </div>

                <div class="row">
                    <div class="row">
                        <h3>Öffentliches Lager</h3>
                    </div>
                    <div class="row">
                        <ul class="pers list-group">
                            <li class="list-group-item">Tisch</li>
                            <li class="list-group-item">Stuhl</li>
                        </ul>
                    </div>
                </div>
            </div>


        </div>

    </div><!-- /.container -->

    <?php
    include_once 'include/footer.php';
}
