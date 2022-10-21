<?php
/**
 * Defines AJAX backend (module) routes only!
 */
return [
    'chat_status' => [
        'path' => '/supportchatswitch/chat-status',
        'target' => \Ubl\SupportchatSwitch\Controller\SwitchController::class . '::getCurrentChatStatus'
    ]
];