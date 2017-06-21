<?php
/**
 * Default Index.php
 * The framework loads this file in. From here on you can build your complete website.
 * DON'T DELETE THIS FILE, BECAUSE YOUR SITE WILL NOT WORK WITHOUT IT.
 */
echo nl2br(FW_Updater::getUpcomingRelease()->description);
?>