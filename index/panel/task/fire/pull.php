<?php namespace x\panel\task\fire;

function pull($_) {
    \extract($GLOBALS, \EXTR_SKIP);
    $n = \basename($path = (string) $_['path']);
    $key = 0 === \strpos($n, 'x.') ? 'x' : (0 === \strpos($n, 'y.') ? 'y' : "");
    $value = "" !== $key ? \substr($n, 2) : $n;
    $_['kick'] = $_REQUEST['kick'] ?? [
        'hash' => null,
        'part' => 0,
        'path' => "" !== $key ? $key . '/1' : ($state->x->panel->route ?? 'asset/1'),
        'query' => \x\panel\_query_set([
            'keep' => null,
            'minify' => null,
            'version' => null
        ]),
        'task' => 'get'
    ];
    // Abort by previous hook’s return value if any
    if (!empty($_['alert']['error'])) {
        return $_;
    }
    if (null !== ($blob = \fetch('https://mecha-cms.com/git-dev/zip/' . $path . \To::query($_['query'] ?? [])))) {
        if (!\is_dir($folder = \ENGINE . \D . 'log' . \D . 'git' . \D . 'zip' . \D . \dirname($path))) {
            \mkdir($folder, 0775, true);
        }
        $version = $_['query']['version'] ?? "";
        if (\file_put_contents($file_core = $folder . \D . $n . ($version ? '@v' . $version : "") . '.zip', $blob) && "" === $key && 'mecha' === $value) {
            $zip_core = new \ZipArchive;
            if (true === $zip_core->open($file_core)) {
                // Pull other extension(s) as well when updating the core to make sure they are up-to-date
                if (\is_file($f = \ENGINE . \D . 'log' . \D . 'git' . \D . 'versions' . \D . \dirname($path) . '.php')) {
                    $cores = [
                        'x.asset' => 1,
                        'x.layout' => 1,
                        'x.link' => 1,
                        'x.markdown' => 1,
                        'x.page' => 1,
                        'x.y-a-m-l' => 1,
                        'y.log' => 1
                    ];
                    $versions = (array) require $f;
                    foreach ($versions as $k => $v) {
                        if (!$v || '^' === $v) {
                            continue; // Skip package(s) that don’t have stable version yet!
                        }
                        if ($value === $k || isset($cores[$k])) {
                            continue; // Already exists in the core ZIP file!
                        }
                        // TODO: Skip extension and layout that has no version change
                        $query = \array_replace_recursive($_['query'] ?? [], ['version' => $version = '^' === $v ? null : $v]);
                        if (null !== ($blob = \fetch('https://mecha-cms.com/git-dev/zip/' . \dirname($path) . '/' . $k . \To::query($query)))) {
                            if (\file_put_contents($file = $folder . \D . $k . ($version ? '@v' . $version : "") . '.zip', $blob)) {
                                // Merge other extension(s) and layout(s) ZIP file(s) to the core ZIP file
                                $zip = new \ZipArchive;
                                if (true === $zip->open($file)) {
                                    $key = 0 === \strpos($k, 'x.') ? 'x' : (0 === \strpos($k, 'y.') ? 'y' : "");
                                    $value = "" !== $key ? \substr($k, 2) : $k;
                                    $prefix = "" !== $key ? 'lot/' . $key . '/' . $value . '/' : "";
                                    for ($i = 0; $i < $zip->numFiles; ++$i) {
                                        $name = \strtr($zip->getNameIndex($i), "\\", '/');
                                        if ('/' === \substr($name, -1)) {
                                            $zip_core->addEmptyDir($prefix . \substr($name, 0, -1));
                                        } else {
                                            $value = $zip->getFromIndex($i);
                                            $zip_core->addFromString($prefix . $name, $value);
                                        }
                                    }
                                    $zip->close();
                                    \unlink($file);
                                }
                            }
                        }
                    }
                }
                $zip_core->close();
            }
        }
    }
    return $_;
}