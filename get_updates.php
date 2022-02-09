<?php

require_once ('TelegramBot.php');

$bot = new TelegramBot();
$bot->getUpdates();
