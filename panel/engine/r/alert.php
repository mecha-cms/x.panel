<?php

if ($_['i'] && $q = ($_GET['q'] ?? null)) {
    if ($_['i'] === 1) {
        Alert::info($language->alertInfoSearch('<em>' . $q . '</em>') . ' <a class="f:r" href="' . $url->clean . '/1' . $url->query('&', ['q' => false]) . $url->hash . '" title="' . $language->doClose . '">' . _\lot\x\panel\h\icon('M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z')[0] . '</a>');
    } else {
        Alert::let('info');
    }
}