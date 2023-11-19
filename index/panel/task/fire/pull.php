<?php namespace x\panel\task\fire;

function pull($_) {
    // Abort by previous hook’s return value if any
    if (isset($_['kick']) || !empty($_['alert']['error'])) {
        return $_;
    }
    \extract($GLOBALS, \EXTR_SKIP);
    $n = \basename($path = (string) $_['path']);
    $key = 0 === \strpos($n, 'x.') ? 'x' : (0 === \strpos($n, 'y.') ? 'y' : "");
    $value = "" !== $key ? \substr($n, 2) : $n;
    $_['kick'] = $_REQUEST['kick'] ?? [
        'hash' => null,
        'part' => 0,
        'path' => "" !== $key ? $key . '/1' : 'asset/1',
        'query' => \x\panel\_query_set([
            'keep' => null,
            'minify' => null,
            'target' => null,
            'version' => null
        ]),
        'task' => 'get'
    ];
    if (null !== ($blob = \fetch('https://' . (\defined("\\TEST") && \TEST ? 'dev.' : "") . 'mecha-cms.com/git/zip/' . $path . \To::query($_['query'] ?? [])))) {
        if (!\is_dir($folder = \ENGINE . \D . 'log' . \D . 'git' . \D . 'zip' . \D . \dirname($path))) {
            \mkdir($folder, 0775, true);
        }
        $version = $_['query']['version'] ?? "";
        // Core update
        if ("" === $key && 'mecha' === $value && \file_put_contents($file_core = $folder . \D . $n . ($version ? '@v' . $version : "") . '.zip', $blob)) {
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
                        $key = 0 === \strpos($k, 'x.') ? 'x' : (0 === \strpos($k, 'y.') ? 'y' : "");
                        if ("" === $key) {
                            continue; // We only look for extension(s) and layout(s)
                        }
                        $value = \substr($k, 2);
                        $prefix = 'lot/' . $key . '/' . $value . '/';
                        $query = \array_replace_recursive($_['query'] ?? [], ['version' => $version = $v]);
                        if (\is_file($file = \LOT . \D . $key . \D . $value . \D . 'about.page')) {
                            $page = new \Page($file);
                            // Skip extension(s) and layout(s) that does not have version change
                            if ($version === $page->version) {
                                $zip_core->addEmptyDir(\substr($prefix, 0, -1));
                                $zip_core->addFromString($prefix . '.keep', "");
                                continue;
                            }
                        }
                        if (null !== ($blob = \fetch('https://mecha-cms.com/' . (\defined("\\TEST") && \TEST ? 'git-dev' : 'git') . '/zip/' . \dirname($path) . '/' . $k . \To::query($query)))) {
                            if (\file_put_contents($file = $folder . \D . $k . ($version ? '@v' . $version : "") . '.zip', $blob)) {
                                // Merge other extension(s) and layout(s) ZIP file(s) to the core ZIP file
                                $zip = new \ZipArchive;
                                if (true === $zip->open($file)) {
                                    for ($i = 0; $i < $zip->numFiles; ++$i) {
                                        $name = \strtr($zip->getNameIndex($i), "\\", '/');
                                        if ('/' === \substr($name, -1)) {
                                            $zip_core->addEmptyDir($prefix . \substr($name, 0, -1));
                                        } else {
                                            $zip_core->addFromString($prefix . $name, $zip->getFromIndex($i));
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
            return $_;
        }
        // Extension and layout update
        if ($key && \file_put_contents($file = $folder . \D . $n . ($version ? '@v' . $version : "") . '.zip', $blob)) {
            return $_;
        }
    }
    $_['alert']['error'][$path] = ['Failed to pull ' . ('x' === $key ? 'extension' : ('y' === $key ? 'layout' : 'package')) . ' %s due to a network error.', ['<a href="" target="_blank">' . $path . '</a>']];
    return $_;
}