<?php

namespace BERGWERK\BwrkAjaxcontent\Controller;

use BERGWERK\BwrkAjaxcontent\Domain\Model\Content;
use BERGWERK\BwrkAjaxcontent\Domain\Model\Pages;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Extensionmanager\Controller\ActionController;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class MenuController extends ActionController
{
    /**
     * @var \BERGWERK\BwrkAjaxcontent\Domain\Repository\ContentRepository
     * @inject
     */
    protected $contentRepository;

    /**
     * @var \BERGWERK\BwrkAjaxcontent\Domain\Repository\PagesRepository
     * @inject
     */
    protected $pageRepository;

    /**
     * @var bool
     */
    protected $isError = false;

    /**
     * @var int
     */
    protected $activePage = 0;

    /**
     * @var int
     */
    protected $pageId = 0;

    public function indexAction()
    {
        $pluginUid = $this->settings['plugin'];

        if (empty($pluginUid)) $this->addMessage('No Plugin Uid');

        if(!$this->isError)
        {
            /** @var Content $contentElement */
            $contentElement = $this->contentRepository->findByUid($pluginUid);
            $contentElementFlexForm = $contentElement->getPiFlexform();

            if(empty($contentElementFlexForm)) $this->addMessage('No content plugin configured.');

            if(!$this->isError) {
                $flexForm = $this->convertFlexForm($contentElementFlexForm);
                if (!isset($flexForm['settings.pages']) || empty($flexForm['settings.pages'])) $this->addMessage('No pages in content plugin configured.');

                if(!$this->isError) {
                    $whichPages = explode(',', $this->getFlexFormValue($flexForm['settings.pages']));

                    if(empty($this->activePage)) $this->activePage = $whichPages[0];

                    $pagesArr = array();
                    foreach($whichPages as $page)
                    {
                        $subPages = array();

                        /** @var Pages $dbPage */
                        $dbPage = $this->pageRepository->findByUid($page);
                        if(is_null($dbPage)) continue;

                        $active = false;
                        if($this->activePage == $dbPage->getUid()) $active = true;

                        if($this->settings['menuRecursive'])
                        {
                            $subPages = $this->getPagesRecursive($dbPage);
                        }

                        $pagesArr[] = array(
                            'page' => $dbPage,
                            'active' => $active,
                            'sub' => $subPages
                        );
                    }

                    $this->view->assign('pageId', $this->pageId);
                    $this->view->assign('pages', $pagesArr);
                    $this->view->assign('settings', $this->settings);
                }
            }
        }
    }

    public function getPagesRecursive($page)
    {
        $subPages = $this->pageRepository->findByPid($page->getUid());

        $pagesArr = array();
        foreach($subPages as $pageUid)
        {
            /** @var Pages $page */
            $page = $this->pageRepository->findByUid($pageUid);
            if(is_null($page)) return '';

            $active = false;
            if($this->activePage == $page->getUid()) $active = true;

            $pagesArr[] = array(
                'page' => $page,
                'active' => $active,
                'sub' => $this->getPagesRecursive($page)
            );
        }
        return $pagesArr;
    }

    protected function convertFlexForm($flexForm)
    {
        if (!is_array($flexForm) && $flexForm) {
            $flexForm = GeneralUtility::xml2array($flexForm);
            if (!is_array($flexForm)) {
                $flexForm = array();
            }
        }

        return $flexForm['data']['general']['lDEF'];
    }

    protected function getFlexFormValue($data)
    {
        return $data['vDEF'];
    }


    protected function addMessage($text, $header='Error', $type=\TYPO3\CMS\Core\Messaging\FlashMessage::WARNING, $session=false)
    {
        /** @var FlashMessage $message */
        $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
            $text,
            $header,
            $type,
            $session
        );

        $this->isError = true;

        \TYPO3\CMS\Core\Messaging\FlashMessageQueue::addMessage($message);
    }
}