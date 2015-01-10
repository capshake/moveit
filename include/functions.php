<?php

/**
 * Name der Etage erhalten
 * @param type $floor
 * @return string
 */
function getFloor($floor) {
    if ($floor > 0) {
        return $floor . '. Etage';
    } else if ($floor <= -1) {
        return ($floor*-1) . '. Untergeschoss';
    } else {
        return 'Erdgeschoss';
    }
}
