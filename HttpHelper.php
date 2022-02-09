<?php

/**
 * Class HttpHelper
 */
class HttpHelper
{
    /**
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return bool|string
     */
    public static function sendPost(string $url, $data = [], $headers = [])
    {
        $curl = curl_init($url);
        $options = [
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true,
        ];

        curl_setopt_array($curl, $options);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

}