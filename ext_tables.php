<?php

if (!defined('TYPO3_MODE'))
{
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'BERGWERK.'.$_EXTKEY,
    'Pi1',
    'BERGWERK AjaxContent Viewer'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'BERGWERK.'.$_EXTKEY,
    'Pi2',
    'BERGWERK AjaxContent Menu'
);

// Typoscript
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'BERGWERK AjaxContent');


// Flexform einbinden
$extensionName = strtolower(\TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($_EXTKEY));
$pluginName = strtolower('Pi1');
$pluginSignature = $extensionName . '_' . $pluginName;
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/Viewer.xml');

$pluginName = strtolower('Pi2');
$pluginSignature = $extensionName . '_' . $pluginName;
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/Menu.xml');