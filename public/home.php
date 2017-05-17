home

<?php
// Send mail
Utils::$mail->setSender('Framework', 'noreply@ictdevs.com');
Utils::$mail->setReceiver('Tim Nijjborg', 'timnijborg@hotmail.nl');
Utils::$mail->setSubject('Test mail');
Utils::$mail->setMessage('Mail <br/><br/> Some Mail <br/><br/> Bye.');
var_dump(Utils::$mail->send());
?>