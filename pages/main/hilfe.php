<?php
include_once '../../include/config.php';


if (!$userData->isLoggedIn()) {
    header('location: ' . BASEDIR . 'login');
} else {

    include_once '../../include/header.php';

    include_once '../../include/menu.php';
    ?>
  		  
        <div class="container">
        	<div class="row">
            <div class="col-md-6 col-md-offset-4">
                    <h3>Hilfe für die Benutzung von moveIT</h3>
                </div>
            
 
				<br>
				<br>
				<div class="well col-md-8 col-md-offset-2">
					<div id="akkordeon">
						<h3> +  Wie werden Räume ausgewählt? </h3>
						<div>
							</b>Wenn Sie sich einloggen, können Sie zunächst aus Ihren Altbauräumen und den zu bearbeitenden Neubauraum auswählen. Dies können Sie entweder über das Dropdown Menü im Altbau- bzw. Neubauabschnitts machen, oder aber über das <b>Map-Symbol</b> einen Raum selektieren. Das Map-Symbol dient außerdem zur Ortung ihres Raums im jeweiligen Gebäude. <br>
							<a href="#" data-toggle="modal" data-target="#lightbox"> 
							<img class="anleitung" src="./img/1Anleitung.png">
							</a>
						</div>
                    <br><br>
						<h3>+  Wie werden Möbel positioniert? </h3>					
							<div>
					
							</b> Durch <b>drag-and-drop</b>, d.h. "Ziehen und Ablegen", bewegen sie die Möbelstücke aus Ihrem Büro im Altbau in den "NeubauPlaner". Dort erscheint ein Symbol für das jeweilige Möbelstück, das Sie durch Doppelklick <b>rotieren</b> können.<br>
							<a href="#" data-toggle="modal" data-target="#lightbox"> 
							<img class="anleitung" src="./img/2Anleitung.png">
							</a>
							
						</div>
					<br><br>
						<h3>+  Was ist die Zollstock-Funktion? </h3>
					
					
							<div>
								</b> Die <b>Zollstock</b>-Funktion dient zur Abmessung von Abständen zwischen Möbelstücken. Dafür wählen Sie die Zollstock-Funktion aus, klicken dann einmal, um den Startpunkt festzulegen, und ein zweites mal, um den Endpunkt auszuwählen. <br>
								<a href="#" data-toggle="modal" data-target="#lightbox"> 
								<img class="anleitung" src="./img/3Anleitung.png">
								</a>
						</div>
					<br><br>
						<h3>+  Wofür dienen die verschiedenen Lager? </h3>
							<div>
								</b> Falls Sie zunächst keine Verwendung für ein oder mehrere Möbelstücke finden, können Sie diese ebenfalls mittels drag-and-drop in das (persönliche) <b>Lager</b> verschieben.
								Möbelstücke, die defekt oder nicht mehr brauchbar sind, verschieben Sie in den <b>"Müll"</b>. Das <b>öffentliche Lager</b> dient der Möbelablage, die noch funktionstüchtig sind, für Sie aber keine weitere Verwendung haben.
								<a href="#" data-toggle="modal" data-target="#lightbox"> 
								<img class="anleitung" src="./img/4Anleitung.png">
								</a>
							</div>
					</div>
					<br><br> Falls Sie noch weitere Fragen, über die Verwendung unserer Website haben, schreiben sie unserem Admin eine E-Mail: <a href="mailto: admin@moveit.de">admin@moveit.de</a></p>
                <hr class="col-md-offset-2">
				</div>
			</div>
               
		
<div id="lightbox" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <button type="button" class="close hidden" data-dismiss="modal" aria-hidden="true">×</button>
        <div class="modal-content">
            <div class="modal-body">
                <img src="" alt="" />
            </div>
        </div>
    </div>
</div>

<?php
    include_once '../../include/footer.php';
}