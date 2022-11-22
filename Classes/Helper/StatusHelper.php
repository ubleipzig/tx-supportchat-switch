<?php
declare(strict_types=1);

/**
 * Class ConfigurationHelper
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

namespace Ubl\SupportchatSwitch\Helper;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Class ConfigurationHelper
 *
 * Provides commandline interface to cleanup past bookings
 *
 * @package Ubl\SupportchatSwitch\Helper
 */

class StatusHelper
{
    /** Get defined path to json status file.
     *
     * @return string   Path to json status file.
     * @access public
     */
    public static function getJsonPath(): string
    {
        return ExtensionManagementUtility::extPath('supportchat-switch') . 'Configuration/Status/state.json';
    }

    /**
     * Return current state of chat as json if operation fails return state 'false'
     *
     * @return string
     * @access public
     */
    public static function getCurrentStatus(): string
    {
        return (false === ($content = file_get_contents(self::getJsonPath())))
            ? "{'state':false}"
            : $content;
    }

    /**
     * Save status as json file.
     *
     * @param bool $state
     *
     * @return bool
     * @access protected
     * @throws JsonException
     */
    public static function saveStatusToJson(bool $state): bool
    {
        try {
            $switchStatusArray = ['state' => $state];
            file_put_contents(
                self::getJsonPath(),
                json_encode($switchStatusArray, JSON_THROW_ON_ERROR)
            );
        } catch (\JsonException $e) {
            throw new Exception('Json has thrown an error: ' . $e->getMessage());
            return false;
        }
        return true;
    }

}