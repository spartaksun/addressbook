<?php

$psrRoot = array(
    'namespace' => 'spartaksun/addresses',
    'directory' => '/src',
);

spl_autoload_register(function ($className) use($psrRoot) {
    $className = ltrim($className, '\\');
    $fileName = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
    $fileName = str_replace($psrRoot['namespace'], '', $fileName);

    require __DIR__ . $psrRoot['directory'] . $fileName;
});