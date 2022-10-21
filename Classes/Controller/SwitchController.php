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

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
* Class ChatsController
*
* Provides commandline interface to cleanup past bookings
*
* @package Ubl\SupportchatStats\Controller
*/
class SwitchController extends BaseAbstractController
{
    /**
     * Initialize action
     *
     * @return void
     * @access public
     */
    public function indexAction()
    {
        if (false === (file_get_contents($this->getJsonPath()))) {
            $this->saveStatusToJson(false);
        }
        if ($this->getBackendRequestParameter('state') !== null) {
            $this->saveStatusToJson((bool)$this->getBackendRequestParameter('state'));
        }
        $status = json_decode(file_get_contents($this->getJsonPath()), true);
        $this->view->assignMultiple([
            'status' => $status['state'],
            'translations' => json_encode([
                'switch_on_message' =>  addslashes($this->translate("module.switch.on.message")),
                'switch_off_message' => addslashes($this->translate("module.switch.off.message"))
			]),
			'frequencyOfChatRequest' => 10000 # in ms
        ]);
    }

    /**
     * Initializes the module
     *
     * @param string $parameter
     *
     * @return mixed
     * @access protected
     */
    protected function getBackendRequestParameter(string $parameter)
    {
        $moduleNamespace = $this->extensionNamespace . '_' . strtolower(GeneralUtility::_GET('M'));
        return (GeneralUtility::_GP($moduleNamespace)[$parameter]) ?? GeneralUtility::_GP($moduleNamespace)[$parameter];
    }

    /**
     * Return current state of chat as json
     *
     * @return string
     * @access public
     */
    public function getCurrentChatStatus(): string
    {
        $json = file_get_contents($this->getJsonPath());
        ob_clean();
        header('Expires: Mon, 26 Jul 1990 05:00:00 GMT');
        header('Last-Modified: ' . gmdate( "D, d M Y H:i:s" ) . 'GMT');
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Content-Length: '.strlen($json));
        header('Content-Type: ' . 'text/json');
        echo $json;
        exit();
    }


    /** Get defined path to json status file.
     *
     * @return string   Path to json status file.
     * @access protected
     */
    protected function getJsonPath(): string
    {
        return ExtensionManagementUtility::extPath('supportchat-switch') . 'Configuration/Status/state.json';
    }

    /**
     * Save status as json file.
     *
     * @param bool $status
     *
     * @return bool
     * @access protected
     * @throws JsonException
     */
    protected function saveStatusToJson(bool $state): bool
    {
        try {
            $switchStatusArray = ['state' => $state];
            file_put_contents(
                $this->getJsonPath(),
                json_encode($switchStatusArray, JSON_THROW_ON_ERROR)
            );
        } catch (\JsonException $e) {
            throw new Exception('Json has thrown an error: ' . $e->getMessage());
            return false;
        }
        return true;
    }
}
