<?php

defined('TYPO3_MODE') or die();

$fields = [
    'tx_impexp_origuid' => [
        'config' => [
            'type' => 'passthrough'
        ]
    ]
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_template', $fields);
