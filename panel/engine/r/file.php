<?php

File::$config['type'] = array_replace(File::$config['type'] ?? [], state('panel', 'type'));
File::$config['x'] = array_replace(File::$config['x'] ?? [], state('panel', 'x'));