<?php

require_once 'params.php';
require_once 'HttpHelper.php';

/**
 * Class TelegramBot
 */
class TelegramBot
{
    public const TRACKED_CURRENCIES = [
        'USD',
        'EUR'
    ];

    /**
     * @var string
     */
    private $botApiUrl;

    /**
     * @var string
     */
    private $cbrCurrencies = 'https://cbr.ru/scripts/XML_daily.asp';

    /**
     * TelegramBot constructor.
     */
    public function __construct()
    {
        $this->botApiUrl = 'https://api.telegram.org/bot' . TOKEN;
    }

    /**
     * getUpdates bot method
     */
    public function getUpdates()
    {
        $updates = HttpHelper::sendPost($this->botApiUrl . '/getUpdates');
        $file = fopen('updates.json', 'at');
        fwrite($file, $updates);
        fclose($file);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getCurrencies()
    {
        $xml = new SimpleXMLElement($this->cbrCurrencies, 0, true);
        $result = [];

        foreach ($xml->Valute as $item) {
            $code = (string)$item->CharCode;

            if (in_array($code, self::TRACKED_CURRENCIES)) {
                $result[$code] = (string)$item->Value;
            }
        }

        $message = $this->getCurrentMsk() . "\n" . "USD: " . $result['USD'] . "\n" . "EUR: " . $result['EUR'];

        $this->sendMessage($message);
    }

    /**
     * @param string $message
     * @return bool|string
     */
    private function sendMessage(string $message)
    {
        $updates = json_decode(file_get_contents('updates.json'), true);
        $chatId = $updates['result'][0]['my_chat_member']['chat']['id'];

        $data = [
            'chat_id' => $chatId,
            'text' => $message
        ];

        return HttpHelper::sendPost($this->botApiUrl . '/sendMessage', $data);
    }

    /**
     * @return string
     */
    private function getCurrentMsk()
    {
        $timezone = new DateTimeZone('Europe/Moscow');
        $date = new DateTime();
        $date->setTimezone($timezone);

        return $date->format('Y-m-d H:i');
    }

}