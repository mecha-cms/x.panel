<?php

if ($_['i'] && $q = $_GET['q'] ?? null) {
    Alert::info('search', '<em>' . $q . '</em>');
}