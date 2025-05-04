<?php

// Yo!

echo new Panel\Y(['lot' => [
    0 => ['type' => 'field', 'content' => 'a field', 'stack' => 1],
    1 => ['type' => 'fields', 'content' => 'a fields', 'stack' => 2],
]]);

exit;