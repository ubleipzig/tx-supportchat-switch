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
     * Initializes the current action
     *
     * @return void
     * @access public
     * @throws JsonException
     */
    public function indexAction()
    {
        $stateJsonPath = ExtensionManagementUtility::extPath('supportchat-switch') . 'Configuration/Status/state.json';
        try {
            if (false === ($stateJson = file_get_contents($stateJsonPath))) {
                $initStatusArray = ['state' => false];
                $stateJson = file_put_contents(
                    $stateJsonPath,
                    json_encode($initStatusArray, JSON_THROW_ON_ERROR)
                );
            }
            $status = (json_decode($stateJson, true));
        } catch (\JsonException $ej) {
            throw new Exception('Json has thrown an error: ' . $ej->getMessage());
        }
        $this->view->assignMultiple([
            'status' => $status['state']
        ]);
    }
}