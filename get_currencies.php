<?php

require_once ('TelegramBot.php');

$bot = new TelegramBot();
print_r($bot->getCurrencies());