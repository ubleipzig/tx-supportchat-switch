# tx-supportchat-switch
Enable and disable chat for Typo3 extension [Supportchat][1]

## Features
* Backend
    * Displays button to switch on/off chat
* Frontend
    * Returns JSON by AJAX route ```supportchatswitch_status```with state:true/false
    * _Please note!_ All following logical steps have to be integrated separately at Typo3 frontend.

## Requirements
* Typo3 > 8.7 < 9.5.99
* Supportchat > 2.6

## Installation

### Installation using Composer

Best practise is installing the extension by using [Composer][2]. In your Composer based TYPO3 project root, just do `composer require ubleipzig/tx-supportchat-switch`.

### Installation as extension from TYPO3 Extension Repository (TER)

Download and install the extension with the extension manager module.

[1]: https://github.com/ubleipzig/tx-supportchat
[2]: https://getcomposer.org/
