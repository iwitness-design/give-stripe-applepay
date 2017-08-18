<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit43b0a8691325457a1ec5f5ff18b35231
{
    public static $prefixLengthsPsr4 = array (
        'G' => 
        array (
            'GSA\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'GSA\\' => 
        array (
            0 => __DIR__ . '/../..' . '/inc',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit43b0a8691325457a1ec5f5ff18b35231::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit43b0a8691325457a1ec5f5ff18b35231::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}