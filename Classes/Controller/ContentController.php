<?php
namespace BERGWERK\BwrkAjaxcontent\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Georg Dümmler <gd@bergwerk.ag>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 *
 * @author	Georg Dümmler <gd@bergwerk.ag>
 * @package	TYPO3
 * @subpackage	bwrk_ajaxcontent
 ***************************************************************/

use BERGWERK\BwrkAjaxcontent\Domain\Model\Pages;
use BERGWERK\BwrkAjaxcontent\ViewHelpers\ContentViewHelper;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class ContentController
 * @package BERGWERK\BwrkAjaxcontent\Controller
 */
class ContentController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @var string
     */
    protected $extKey;

    /**
     * @var \BERGWERK\BwrkAjaxcontent\Domain\Repository\ContentRepository
     * @inject
     */
    protected $contentRepository;

    /**
     * @var \BERGWERK\BwrkAjaxcontent\Domain\Repository\PagesRepository
     * @inject
     */
    protected $pagesRepository;

    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     * @inject
     */
    protected $configurationManager;

    /**
     * Initializes the controller before invoking an action method.
     *
     * Override this method to solve tasks which all actions have in
     * common.
     *
     * @return void
     * @api
     */
    protected function initializeAction()
    {
        $this->extKey = 'bwrk_ajaxcontent';
    }

    public function indexAction()
    {
        $pages = $this->settings['pages'];
        $object = array();

        if(strlen($pages) > 0)
        {
            $pageIds = explode(',', $pages);

            $cObj = $this->configurationManager->getContentObject();
            $cObjData = $cObj->data;
            $this->view->assign('cid', $cObjData['uid']);

            if(count($pageIds) > 0)
            {
                if(strlen($pageIds[0]) > 0)
                {
                    $i=0;
                    foreach($pageIds as $pageId)
                    {
                        $this->loadContentByPage($pageId, $object, $i);

                        $i++;
                    }
                }
            }
        } else {
            $this->addFlashMessage('No Pages Defined!', $this->extKey, AbstractMessage::ERROR);
        }

        $this->view->assign('settings', $this->settings);
        $this->view->assign('object', $object);
    }

    public function loadAction()
    {
        $request = $this->request->getArguments();
        if(isset($request['uid']) && !empty($request['uid']))
        {
            $i = 0;
            $object = array();
            $this->loadContentByPage($request['uid'], $object, $i);

            $this->view->assign('settings', $this->settings);
            $this->view->assign('object', $object);
        }
    }

    private function loadContentByPage($pageUid, &$object, &$i)
    {
        /** @var Pages $page */
        $contentElements = $this->contentRepository->getContentByPid($pageUid);

        $page = $this->pagesRepository->findByUid($pageUid);

        if(is_null($page)) return;

        $j=0;
        $tmpContentElements = array();

        foreach ($contentElements as $contentElement)
        {
            $tmpContentElements[$j] = array(
                'uid' => $contentElement->getUid(),
                'pid' => $contentElement->getPid(),
                'header' => $contentElement->getHeader(),
                'sorting' => $contentElement->getSorting(),
            );

            $j++;
        }
        $object[$i]['uid'] = $page->getUid();
        $object[$i]['pid'] = $pageUid;
        $object[$i]['title'] = $page->getTitle();
        $object[$i]['contentElements'] = $tmpContentElements;
    }
}