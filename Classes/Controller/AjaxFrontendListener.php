<?php
declare(strict_types=1);

/**
 * Class SupportChatSwitch Ajax Frontend Listener
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

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Ubl\SupportchatSwitch\Helper\StatusHelper;


/**
 * Class FrontentListener
 *
 * Support Chat Switch ajax frontend listener
 *
 * @package Ubl\SupportchatSwitch\Ajax
 */
class AjaxFrontendListener
{
    /**
     * Process frontend action by ajax calls
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     * @access public
     */
    public function getCurrentStatus(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
    {
        $responseData = StatusHelper::getCurrentStatus();
        $response->getBody()->write($responseData);
        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
}