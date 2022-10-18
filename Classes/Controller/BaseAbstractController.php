<?php
declare(strict_types=1);

/**
 * Class BaseAdministrationController
 *
 * Copyright (C) Leipzig University Library 2022 <info@ub.uni-leipzig.de>
 *
 * @author  Frank Morgner <morgnerf@ub.uni-leipzig.de>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */
namespace Ubl\SupportchatSwitch\Controller;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Class BaseAbstractController
 *
 * Provides commandline interface to cleanup past bookings
 *
 * @package Ubl\SupportchatStats\Controller
 */
abstract class BaseAbstractController extends ActionController
{
    /**
     * Backend Template Container
     *
     * @var string
     */
    protected $defaultViewObjectName
        = \TYPO3\CMS\Backend\View\BackendTemplateView::class;

    /**
     * Extension namespace
     *
     * @var string $extensionNamespace
     * @access protected
     */
    protected $extensionNamespace = "tx_supportchatswitch";

    /**
     * Pageinfo to check access
     *
     * @var mixed
     * @access private
     */
    private $pageInformation;

    /**
     * Page id
     *
     * @var mixed
     * @access private
     */
    private $pageUid;

    /**
     * Initializes the module
     *
     * @return void
     * @access public
     * @throws \Exception    If no chat pid given.
     */
    public function initializeAction()
    {
        $this->pageUid = (int)GeneralUtility::_GET('id');
        $this->pageInformation = BackendUtility::readPageAccess($this->pageUid, '');
        parent::initializeAction();
    }

    /**
     * Initialize template view - basic method of backend module
     *
     * @param ViewInterface $view
     */
    protected function initializeView(ViewInterface $view)
    {
        /** @var BackendTemplateView $view */
        parent::initializeView($view);

        if ($view instanceof BackendTemplateView) {
            $view->getModuleTemplate()->getPageRenderer()->loadRequireJsModule('TYPO3/CMS/Backend/Modal');
            //$view->getModuleTemplate()->getPageRenderer()->loadRequireJsModule('TYPO3/CMS/Backend/DateTimePicker');
            $view->getModuleTemplate()->getDocHeaderComponent()->disable();
        }
    }

    /**
     * Helper function to render localized flashmessages
     *
     * @param string  $keyFragment
     * @param integer $severity [optional] Severity code. One of the t3lib_FlashMessage constants
     *
     * @return void
     * @access public
     */
    public function addFlashMessageHelper($keyFragment, $severity = \TYPO3\CMS\Core\Messaging\FlashMessage::OK)
    {
        $messageLocaleKey = sprintf(
            'flashmessage.%s.%s',
            strtolower($this->request->getControllerName()),
            $keyFragment
        );
        $localizedMessage = $this->translate($messageLocaleKey);
        $titleLocaleKey = sprintf('%s.title', $messageLocaleKey);
        $localizedTitle = $this->translate($titleLocaleKey);
        $this->addFlashMessage($localizedMessage, $localizedTitle, $severity);
    }

    /**
     * Translate label
     *
     * @param string $key
     * @param array|null $arguments
     *
     * @return string
     * @access protected
     */
    protected function translate(string $key, array $arguments = null): string
    {
        return LocalizationUtility::translate($key, 'supportchat-stats', $arguments) ?? '';
    }
}
