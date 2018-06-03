<?php

return [
    "target_php_version" => null,
    'directory_list' => [
        'src',
        'vendor/pimple/pimple',
        'vendor/psr/log',
        'vendor/twig/twig'
    ],
    "exclude_analysis_directory_list" => [
        'vendor/'
    ],
    'plugins' => [
        'AlwaysReturnPlugin',
        'UnreachableCodePlugin',
        'DollarDollarPlugin',
        'DuplicateArrayKeyPlugin',
        'PregRegexCheckerPlugin',
        'PrintfCheckerPlugin',
    ],
];
