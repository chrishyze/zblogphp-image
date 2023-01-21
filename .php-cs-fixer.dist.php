<?php

$header = <<<'EOF'
This file is part of zblogphp-image.

@author  chrishyze <chrishyze@gmail.com>
@link    https://github.com/chrishyze/zblogphp-image
@license https://github.com/chrishyze/zblogphp-image/blob/master/LICENSE
EOF;

$finder = PhpCsFixer\Finder::create()->in(__DIR__);
$config = new PhpCsFixer\Config();

return $config->setRules([
    '@PhpCsFixer' => true,
    'declare_strict_types' => true,
    'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
    'header_comment' => [
        'header' => $header,
        'comment_type' => 'PHPDoc',
        'location' => 'after_open',
        'separate' => 'bottom',
    ],
])->setFinder($finder);
