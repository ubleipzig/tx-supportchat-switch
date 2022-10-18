<?php
defined('TYPO3_MODE') || die('Access denied.');

TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
    'module.tx_supportchatswitch_system_supportchatswitchsupportchatswitch {
        view {
            templateRootPaths {
                0 = EXT:supportchat-switch/Resources/Private/Backend/Templates/
                1 = {$module.tx_supportchatswitch.view.templateRootPath}
            }
            partialRootPaths {
                0 = EXT:supportchat-switch/Resources/Private/Backend/Partials/
                1 = {$module.tx_supportchatswitch.view.partialRootPath}
            }
            layoutRootPaths {
                0 = EXT:supportchat-switch/Resources/Private/Backend/Layouts/
                1 = {$module.tx_supportchatswitch.view.layoutRootPath}
            }
        }
        persistence < plugin.tx_supportchatswitch.persistence
    }'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants(
    'module.tx_supportchatswitch_system_supportchatswitchsupportchatswitch {
        view {
            # cat=module.tx_supportchatswitch/file; type=string; label=Path to template root (BE)
            templateRootPath = EXT:supportchat-switch/Resources/Private/Backend/Templates/
            # cat=module.tx_supportchatswitch/file; type=string; label=Path to template partials (BE)
            partialRootPath = EXT:supportchat-switch/Resources/Private/Backend/Partials/
            # cat=module.tx_supportchatswitch/file; type=string; label=Path to template layouts (BE)
            layoutRootPath = EXT:supportchat-switch/Resources/Private/Backend/Layouts/
        }
        persistence {
            # cat=module.tx_supportchatswitch//a; type=string; label=Default storage PID
            storagePid =
        }
    }'
);
