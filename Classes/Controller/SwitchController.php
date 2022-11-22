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

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Ubl\SupportchatSwitch\Helper\StatusHelper;

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
        if (false === (file_get_contents(StatusHelper::getJsonPath()))) {
            StatusHelper::saveStatusToJson(false);
        }
        if ($this->getBackendRequestParameter('state') !== null) {
            StatusHelper::saveStatusToJson((bool)$this->getBackendRequestParameter('state'));
        }
        $status = json_decode(file_get_contents(StatusHelper::getJsonPath()), true);
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
     * @return ResponseInterface
     * @access public
     */
    public function getCurrentChatStatus(ResponseInterface $response): ResponseInterface
    {
        $responseData = StatusHelper::getCurrentStatus();
        $response->getBody()->write($responseData);
        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
}
