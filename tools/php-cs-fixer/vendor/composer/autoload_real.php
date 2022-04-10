<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit4ebc6dd4b8f7cf82886834cc616f5cb8
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInit4ebc6dd4b8f7cf82886834cc616f5cb8', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit4ebc6dd4b8f7cf82886834cc616f5cb8', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        \Composer\Autoload\ComposerStaticInit4ebc6dd4b8f7cf82886834cc616f5cb8::getInitializer($loader)();

        $loader->register(true);

        $includeFiles = \Composer\Autoload\ComposerStaticInit4ebc6dd4b8f7cf82886834cc616f5cb8::$files;
        foreach ($includeFiles as $fileIdentifier => $file) {
            composerRequire4ebc6dd4b8f7cf82886834cc616f5cb8($fileIdentifier, $file);
        }

        return $loader;
    }
}

/**
 * @param string $fileIdentifier
 * @param string $file
 * @return void
 */
function composerRequire4ebc6dd4b8f7cf82886834cc616f5cb8($fileIdentifier, $file)
{
    if (empty($GLOBALS['__composer_autoload_files'][$fileIdentifier])) {
        $GLOBALS['__composer_autoload_files'][$fileIdentifier] = true;

        require $file;
    }
}
