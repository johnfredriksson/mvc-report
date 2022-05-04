<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8f6529a43606c2e718f30f5c38cf3a7e
{
    public static $files = array (
        '5f0e95b8df5acf4a92c896dc3ac4bb6e' => __DIR__ . '/..' . '/phpmetrics/phpmetrics/src/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PhpParser\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PhpParser\\' => 
        array (
            0 => __DIR__ . '/..' . '/nikic/php-parser/lib/PhpParser',
        ),
    );

    public static $prefixesPsr0 = array (
        'H' => 
        array (
            'Hal\\' => 
            array (
                0 => __DIR__ . '/..' . '/phpmetrics/phpmetrics/src',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8f6529a43606c2e718f30f5c38cf3a7e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8f6529a43606c2e718f30f5c38cf3a7e::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit8f6529a43606c2e718f30f5c38cf3a7e::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit8f6529a43606c2e718f30f5c38cf3a7e::$classMap;

        }, null, ClassLoader::class);
    }
}