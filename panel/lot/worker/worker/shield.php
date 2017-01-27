<?php

// Load user function(s) from the current shield folder if any
$__folder_shield = SHIELD . DS . $config->shield . DS;
if ($fn = File::exist($__folder_shield . '__index.php')) require $fn;