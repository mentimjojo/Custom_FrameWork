<?php
/**
 * Standard index.php
 */
Settings::setDebug(false);
Settings::setSSL(true);
Routes::setHome('home.php');
Routes::setRoute('dit/is/een/mooie/route/naar/mijn/bestand/yeey', 'yeey.php');
Routes::setRoute("rens-dit-is-jouwn-supercoole/route-en-dat-vinden-wijHELEMAAL-leuk", "yeey.php");
?>