<?php

namespace BERGWERK\BwrkAjaxcontent\Utility\Eid;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Utility\EidUtility;

class Content {
    public function run()
    {
        $configuration = array(
            'pluginName' => 'Pi3',
            'vendorName' => 'BERGWERK',
            'extensionName' => 'BwrkAjaxcontent',
            'controller' => 'Content',
            'action' => 'load',
            'mvc' => array(
                'requestHandlers' => array(
                    'TYPO3\CMS\Extbase\Mvc\Web\FrontendRequestHandler' => 'TYPO3\CMS\Extbase\Mvc\Web\FrontendRequestHandler'
                )
            ),
            'settings' => array()
        );

        $bootstrap = new \TYPO3\CMS\Extbase\Core\Bootstrap();

        $pId = (GeneralUtility::_GET('id') ? GeneralUtility::_GET('id') : 1);

        $GLOBALS['TSFE'] = GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController', $GLOBALS['TYPO3_CONF_VARS'], $pId, 0, true);
        $GLOBALS['TSFE']->connectToDB();
        $GLOBALS['TSFE']->fe_user = \TYPO3\CMS\Frontend\Utility\EidUtility::initFeUser();
        $GLOBALS['TSFE']->id = $pId;
        $GLOBALS['TSFE']->determineId();
//        $GLOBALS['TSFE']->getCompressedTCarray();
        $GLOBALS['TSFE']->initTemplate();
        $GLOBALS['TSFE']->getConfigArray();
//        $GLOBALS['TSFE']->includeTCA();

        echo $bootstrap->run('', $configuration);
    }
}

$run = GeneralUtility::makeInstance('BERGWERK\\BwrkAjaxcontent\\Utility\\Eid\\Content');
$run->run();