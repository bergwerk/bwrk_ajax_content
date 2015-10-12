<?php

if (!defined('TYPO3_MODE'))
{
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'BERGWERK.' . $_EXTKEY,
    'Pi1',
    array(
        'Content' => 'index'
    ),
    array(
        'Content' => 'index'
    )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'BERGWERK.' . $_EXTKEY,
    'Pi2',
    array(
        'Menu' => 'index'
    ),
    array(
        'Menu' => 'index'
    )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'BERGWERK.' . $_EXTKEY,
    'Pi3',
    array(
        'Content' => 'load'
    ),
    array(
        'Content' => 'load'
    )
);


$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['bwrkAjaxcontentLoad'] = 'EXT:'.$_EXTKEY.'/Classes/Utility/Eid/Content.php';