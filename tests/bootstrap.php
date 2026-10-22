<?php

$host = getenv('LARAHOME_PATH') ?: dirname(__DIR__, 2).'/LaraHome';
if (!is_file($host.'/vendor/autoload.php')) {
    throw new RuntimeException('Install the migrated LaraHome dependencies first.');
}
$loader = require $host.'/vendor/autoload.php';
$loader->addPsr4('Tchoblond59\\LaraLight\\', dirname(__DIR__).'/src', true);
$loader->addPsr4('PluginTests\\', __DIR__, true);

// The host uses Composer's optimized classmap. Override it as well as PSR-4
// so these tests always exercise this checkout rather than vendor copies.
$classMap = [];
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(dirname(__DIR__).'/src'));
foreach ($files as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') {
        continue;
    }
    $source = file_get_contents($file->getPathname());
    if (preg_match('/^namespace\s+([^;]+);/m', $source, $namespace)
        && preg_match('/^(?:abstract\s+|final\s+)?class\s+(\w+)/m', $source, $class)) {
        $classMap[$namespace[1].'\\'.$class[1]] = $file->getPathname();
    }
}
$loader->addClassMap($classMap);
