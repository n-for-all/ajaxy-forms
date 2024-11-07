<?php

declare(strict_types=1);

use Isolated\Symfony\Component\Finder\Finder;

class TwigPrefixer
{

    const TWIG_BASE_DIR = __DIR__ . '/vendor/twig/twig';

    static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if (0 === $length) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

    static function getPrefix()
    {
        return 'Isolated';
    }

    static function getFormattedPrefix($backslashDuplicationFactor = 0, $includeInitialBackslash = true)
    {
        $prefix = self::getPrefix();

        if ($includeInitialBackslash) {
            $prefix = '\\' . $prefix;
        }

        for ($i = 0; $i < $backslashDuplicationFactor; $i++) {
            $prefix = str_replace('\\', '\\\\', $prefix);
        }

        return $prefix;
    }
}


return [
    // The prefix configuration. If a non null value will be used, a random prefix will be generated.
    'prefix' => 'Isolated',
    // 'exclude-functions' => ['/(.*)/i', 'twig_test_empty'],

    // By default when running php-scoper add-prefix, it will prefix all relevant code found in the current working
    // directory. You can however define which files should be scoped by defining a collection of Finders in the
    // following configuration key.
    //
    // For more see: https://github.com/humbug/php-scoper#finders-and-paths
    'finders' => [
        // Finder::create()->files()->in('src'),
        Finder::create()
            ->files()
            ->ignoreVCS(true)
            ->notName('/LICENSE|.*\\.md|.*\\.dist|Makefile|composer\\.json|composer\\.lock/')
            ->exclude([
                'doc',
                'test',
                'test_old',
                'tests',
                'Tests',
                'vendor-bin',
            ])
            ->in('vendor'),
        Finder::create()->append([
            'composer.json',
        ]),
    ],

    // Whitelists a list of files. Unlike the other whitelist related features, this one is about completely leaving
    // a file untouched.
    // Paths are relative to the configuration file unless if they are already absolute
    // 'files-whitelist' => [
    //     'src/a-whitelisted-file.php',
    // ],

    // When scoping PHP files, there will be scenarios where some of the code being scoped indirectly references the
    // original namespace. These will include, for example, strings or string manipulations. PHP-Scoper has limited
    // support for prefixing such strings. To circumvent that, you can define patchers to manipulate the file to your
    // heart contents.
    //
    // For more see: https://github.com/humbug/php-scoper#patchers
    'patchers' => [

        /**
         * Patcher for all files.
         */
        static function (string $filePath, string $prefix, string $contents): string {
            if (strpos($filePath, 'symfony/intl/') === false) {
                return $contents;
            }
            $contents = str_replace(
                'trigger_deprecation',
                '//trigger_deprecation',
                $contents
            );

            return $contents;
        },
        static function (string $filePath, string $prefix, string $contents): string {

            if (strpos($filePath, 'twig/') === false && strpos($filePath, 'twig-bridge/') === false) {
                return $contents;
            }

            if (TwigPrefixer::endsWith($filePath, 'src' . DIRECTORY_SEPARATOR . 'Node' . DIRECTORY_SEPARATOR . 'ModuleNode.php')) {
                // Fix template compilation - add the namespace to the template file.
                $contents = str_replace(
                    'use Twig\\',
                    'use ' . TwigPrefixer::getPrefix() . '\\\\Twig\\',
                    $contents
                );



                // When generating the PHP template, make sure its class declaration doesn't contain the namespace.
                // That's the only place where we don't want to have it.
                // $string_to_remove =  TwigPrefixer::getFormattedPrefix() . '\\';
                // $contents = preg_replace(
                //     '/(->write\s*\(\s*\'class \'\s*\.\s*)(\$compiler\s*->\s*getEnvironment\s*\(\s*\)\s*->\s*getTemplateClass\s*\(\s*\$this\s*->\s*getSourceContext\s*\(\s*\)\s*->\s*getName\s*\(\s*\)\s*,\s*\$this\s*->\s*getAttribute\s*\(\s*\'index\'\s*\)\s*\))/m',
                //     '$1 \\substr( $2, ' . strlen($string_to_remove) . ' ) ',
                //     $contents
                // );

                return $contents;
            }
            if (TwigPrefixer::endsWith($filePath, 'src' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'core.php')) {
                // Fix template compilation - add the namespace to the template file.
                // $contents = str_replace(
                //     'return CoreExtension',
                //     'return ' . TwigPrefixer::getPrefix() . '\\CoreExtension',
                //     $contents
                // );
                $contents = str_replace(
                    'namespace Isolated;',
                    '',
                    $contents
                );
                $contents = str_replace(
                    'trigger_deprecation',
                    '//trigger_deprecation',
                    $contents
                );



                // When generating the PHP template, make sure its class declaration doesn't contain the namespace.
                // That's the only place where we don't want to have it.
                // $string_to_remove =  TwigPrefixer::getFormattedPrefix() . '\\';
                // $contents = preg_replace(
                //     '/(->write\s*\(\s*\'class \'\s*\.\s*)(\$compiler\s*->\s*getEnvironment\s*\(\s*\)\s*->\s*getTemplateClass\s*\(\s*\$this\s*->\s*getSourceContext\s*\(\s*\)\s*->\s*getName\s*\(\s*\)\s*,\s*\$this\s*->\s*getAttribute\s*\(\s*\'index\'\s*\)\s*\))/m',
                //     '$1 \\substr( $2, ' . strlen($string_to_remove) . ' ) ',
                //     $contents
                // );

                return $contents;
            }

            if (TwigPrefixer::endsWith($filePath, 'RenderBlockNode.php') || TwigPrefixer::endsWith($filePath, 'SearchAndRenderBlockNode.php')) {
                // Fix template compilation - add the namespace to the template file.

                $match = "this->env->getRuntime(\\'Symfony\\\\Component\\\\Form\\\\FormRenderer\\')";
                $contents = str_replace(
                    $match,
                    sprintf("this->env->getRuntime(\\'%s\\\\Symfony\\\\Component\\\\Form\\\\FormRenderer\\')", TwigPrefixer::getPrefix()),
                    $contents
                );

                return $contents;
            }

            // Hardcoded class names in code
            // $contents = preg_replace(
            //     '/("|\')((\\\\){1,2}Twig(\\\\){1,2}[A-Za-z\\\\]+)\1/m',
            //     '$1' . TwigPrefixer::getFormattedPrefix(2) . '$2$1',
            //     $contents
            // );

            // Hardcoded "use" statements

            $contents = preg_replace(
                '/use\s+(Twig)(\\\\){1,2}/m',
                'use ' . TwigPrefixer::getFormattedPrefix(2) . '\\\\\\\\Twig\\\\\\\\',
                $contents
            );

            // Add namespaces to generated Twig template names
            // $contents = preg_replace(
            //     '/(\'|")(__TwigTemplate_)\1/m',
            //     '$1' . TwigPrefixer::getFormattedPrefix(2) . '\\\\\\\\$2$1',
            //     $contents
            // );

            return $contents;
        },

        /**
         * Patcher for \$prefix\Twig\Extension\CoreExtension.
         */
        static function (string $filePath, string $prefix, string $contents): string {
            // Fix the usage of global twig_* and _twig_* functions.
            if (TwigPrefixer::endsWith($filePath, 'src' . DIRECTORY_SEPARATOR . 'Extension' . DIRECTORY_SEPARATOR . 'CoreExtension.php')) {
                $contents = preg_replace(
                    '/(new ' . TwigPrefixer::getFormattedPrefix(1) . '\\\\Twig\\\\TwigFilter\(\s*\'[^\']+\'\s*,\s*\')((_)?twig_[^\']+\')/m',
                    '$1' . TwigPrefixer::getFormattedPrefix(2) . '\\\\\\\\$2',
                    $contents
                );

                // Handle the occurrence in the is_safe_callback array element.
                $contents = preg_replace(
                    '/(new ' . TwigPrefixer::getFormattedPrefix(1) . '\\\\Twig\\\\TwigFilter\(\s*\'[^\']+\'\s*,\s*\'.*twig_[^\']+\',\s*\[[^\]]*,\s*\'is_safe_callback\'\s*=>\s*\')((_)?twig_[^\']+\'\s*\]\s*\))/m',
                    '$1' . TwigPrefixer::getFormattedPrefix(2) . '\\\\\\\\$2',
                    $contents
                );

                // Handle the occurrence in the preg_replace_callback.
                $contents = preg_replace(
                    '/(preg_replace_callback.*,\s*\')(.+\'.*;)/m',
                    '$1' . TwigPrefixer::getFormattedPrefix(2) . '\\\\\\\\$2',
                    $contents
                );

                // Handle the occurrence in the array_walk_recursive.
                $contents = preg_replace(
                    '/(array_walk_recursive.*,\s*\')(.+\'.*;)/m',
                    '$1' . TwigPrefixer::getFormattedPrefix(2) . '\\\\\\\\$2',
                    $contents
                );
            }

            return $contents;
        },

        /**
         * Patcher for \$prefix\Twig\Environment.
         */
        static function (string $filePath, string $prefix, string $contents): string {
            // Fix the usage of Twig\\Extension\\AbstractExtension.
            if (TwigPrefixer::endsWith($filePath, 'src' . DIRECTORY_SEPARATOR . 'Environment.php')) {
                $contents = preg_replace(
                    '/(Twig\\\\.+\\\\AbstractExtension)/m',
                    TwigPrefixer::getFormattedPrefix(2, false) . '\\\\\\\\$1',
                    $contents
                );
            }

            return $contents;
        },
    ],

    // PHP-Scoper's goal is to make sure that all code for a project lies in a distinct PHP namespace. However, you
    // may want to share a common API between the bundled code of your PHAR and the consumer code. For example if
    // you have a PHPUnit PHAR with isolated code, you still want the PHAR to be able to understand the
    // PHPUnit\Framework\TestCase class.
    //
    // A way to achieve this is by specifying a list of classes to not prefix with the following configuration key. Note
    // that this does not work with functions or constants neither with classes belonging to the global namespace.
    //
    // Fore more see https://github.com/humbug/php-scoper#whitelist
    'whitelist' => [
        // 'PHPUnit\Framework\TestCase',   // A specific class
        // 'PHPUnit\Framework\*',          // The whole namespace
        // '*',                            // Everything
    ],

    // If `true` then the user defined constants belonging to the global namespace will not be prefixed.
    //
    // For more see https://github.com/humbug/php-scoper#constants--constants--functions-from-the-global-namespace
    // 'whitelist-global-constants' => true,

    // If `true` then the user defined classes belonging to the global namespace will not be prefixed.
    //
    // For more see https://github.com/humbug/php-scoper#constants--constants--functions-from-the-global-namespace
    // 'whitelist-global-classes' => true,

    // If `true` then the user defined functions belonging to the global namespace will not be prefixed.
    //
    // For more see https://github.com/humbug/php-scoper#constants--constants--functions-from-the-global-namespace
    // 'whitelist-global-functions' => true,
];
