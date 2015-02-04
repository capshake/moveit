<?php
include_once 'include/config.php';


if (!$userData->isLoggedIn()) {
    header('location: ' . BASEDIR . 'login');
} else {

    $headerTitle = 'moveIT';


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
                    Trakt/Gebäude:<br>
                    <select class="trakt">
                        <option value="1">H-Trakt</option>
                    </select>
                </div>
                <div class="row">
                    Etage:<br>
                    <select class="etage">
                        <option value="-2">2. UG</option>
                        <option value="-1">1. UG</option>
                    </select>
                </div>
                <div class="row">
                    Raum:<br>
                    <select class="raum">
                        <option value="1">H1.11</option>
                    </select>
                </div>
                
                <div class="row">
                    <ul class="altbau liste">
                        <li>Tisch</li>
                        <li>Stuhl</li>
                    </ul>
                </div>
            </div>

            <!-- Neubau -->
            <div class="col-md-6 neubau">
                <div class="row">
                    <h3>Neubau</h3>
                </div>

                <div class="row">
                    Trakt/Gebäude:
                    <select class="trakt">
                        <option value="1">H-Trakt</option>
                    </select>

                    Etage:
                    <select class="etage">
                        <option value="-2">2. UG</option>
                        <option value="-1">1. UG</option>
                    </select>

                    Raum:
                    <select class="raum">
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
                        <ul class="altbau liste">
                            <li>Tisch</li>
                            <li>Stuhl</li>
                        </ul>
                    </div>
                </div>

                <div class="row">
                    <div class="row">
                        <h3>Öffentliches Lager</h3>
                    </div>
                    <div class="row">
                        <ul class="altbau liste">
                            <li>Tisch</li>
                            <li>Stuhl</li>
                        </ul>
                    </div>
                </div>
            </div>


        </div>

    </div><!-- /.container -->

    <?php
    include_once 'include/footer.php';
}