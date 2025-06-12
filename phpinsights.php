<?php

use NunoMaduro\PhpInsights\Domain\Insights\CyclomaticComplexityIsHigh;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenDefineFunctions;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenNormalClasses;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenTraits;
use NunoMaduro\PhpInsights\Domain\Sniffs\ForbiddenSetterSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\UselessOverridingMethodSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\ForbiddenFunctionsSniff;
use PHP_CodeSniffer\Standards\PSR1\Sniffs\Files\SideEffectsSniff;
use PhpCsFixer\Fixer\ClassNotation\OrderedClassElementsFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\Import\SingleImportPerStatementFixer;
use PhpCsFixer\Fixer\StringNotation\ExplicitStringVariableFixer;
use SlevomatCodingStandard\Sniffs\Classes\ForbiddenPublicPropertySniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousAbstractClassNamingSniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousExceptionNamingSniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousInterfaceNamingSniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousTraitNamingSniff;
use SlevomatCodingStandard\Sniffs\Commenting\DocCommentSpacingSniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\DisallowShortTernaryOperatorSniff;
use SlevomatCodingStandard\Sniffs\Functions\FunctionLengthSniff;
use SlevomatCodingStandard\Sniffs\Functions\StaticClosureSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\AlphabeticallySortedUsesSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\UseSpacingSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\DeclareStrictTypesSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\DisallowArrayTypeHintSyntaxSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\ParameterTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\PropertyTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\ReturnTypeHintSniff;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenSecurityIssues;

return [
    'preset' => 'default',
    'diff_context' => 1,
    'exclude' => [
        'bin',
        'var',
        'config',
        'docker',
        'public',
        'runtime',
        'tests',
        'migrations',
        'seeders',
        'phpinsights.php',
        'app/Model',
        'app/Exception',
        'app/Listener/DbQueryExecutedListener.php',
        'app/Listener/ResumeExitCoordinatorListener.php',
        'app/Infra/Database',
        'app/Infra/Http',
        'app/Infra/Http',
        'app/Infra/Validator',
    ],
    'add' => [
    ],
    'remove' => [
        UselessOverridingMethodSniff::class,
        ForbiddenPublicPropertySniff::class,
        PropertyTypeHintSniff::class,
        ReturnTypeHintSniff::class,
        SuperfluousInterfaceNamingSniff::class,
        SuperfluousAbstractClassNamingSniff::class,
        SuperfluousExceptionNamingSniff::class,
        SuperfluousTraitNamingSniff::class,
        StaticClosureSniff::class,
        DeclareStrictTypesSniff::class,
        DisallowShortTernaryOperatorSniff::class,
        ExplicitStringVariableFixer::class,
        OrderedImportsFixer::class,
        OrderedClassElementsFixer::class,
        SingleImportPerStatementFixer::class,
        DocCommentSpacingSniff::class,
        AlphabeticallySortedUsesSniff::class,
        ForbiddenTraits::class,
        SideEffectsSniff::class,
        ForbiddenFunctionsSniff::class,
        ForbiddenNormalClasses::class,
        DisallowArrayTypeHintSyntaxSniff::class,
        ForbiddenSetterSniff::class,
        UseSpacingSniff::class,
        ForbiddenDefineFunctions::class,
        ParameterTypeHintSniff::class,
        ForbiddenSecurityIssues::class,
    ],
    'config' => [
        LineLengthSniff::class => [
            'lineLimit' => 120,
            'absoluteLineLimit' => 120
        ],
        FunctionLengthSniff::class => [
            'maxLinesLength' => 100
        ],
        CyclomaticComplexityIsHigh::class => [
            'maxComplexity' => 3,
        ]
    ],
];

