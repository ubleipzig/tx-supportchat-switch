<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$boot = function () {

    if (TYPO3_MODE === 'BE') {
        /* ===========================================================================
            Register BE-Modules
        =========================================================================== */

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
            'Ubl.SupportchatSwitch',
            'system',
            'supportchatswitch',
            'top',
            [
                'Switch' => 'index'
            ],
            [
                'access' => 'user,group',
                'icon' => 'EXT:supportchat-switch/Resources/Public/Icons/module-supportchat-switch.svg',
                'labels' => 'LLL:EXT:supportchat-switch/Resources/Private/Language/locallang_mod.xlf',
            ]
        );
    }

    /***************************************************************
     * TCA
     */

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
        'tx_supportchatswitch',
        'EXT:supportchat-switch/Resources/Private/Language/locallang.xlf'
    );
};

$boot();
unset($boot);
