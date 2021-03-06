<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitfa85b606e08e4732352bd28ce2b3249f
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitfa85b606e08e4732352bd28ce2b3249f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitfa85b606e08e4732352bd28ce2b3249f::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
