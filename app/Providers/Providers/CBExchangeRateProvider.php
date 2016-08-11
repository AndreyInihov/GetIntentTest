<?php

namespace App\Providers;

use App\Providers\ExchangeRateProviderInterface as ExchnageRateProviderInterface;
use App\Providers\ExchangeRateProviderHelper as ExchangeRateProviderHelper;

use GuzzleHttp\Client;

class CBExchangeRateProvider extends ExchangeRateProviderHelper implements ExchnageRateProviderInterface
{
    // Адрес сайта ЦБ
    const BASE_URL = "http://www.cbr.ru";

    // Адрес вызываемой функции
    const API_FUNC = "/scripts/XML_daily.asp";

    // Тип производимого запроса
    const REQUEST_TYPE = "POST";

    /**
    * Метод возвращает массив, содержащий обменные курсы валют, полученные от API ЦБ
    *
    * Пример: [ 'USD' => 73.0865, 'EUR' => 80.1231 ]
    *
    * @var array $curreccies Массив, содержащий трехзначные коды валют
    *
    * @return array
    */
    public function requestRates($currencies) {
        $client = new Client([
            'base_uri' => self::BASE_URL,
        ]);

        $response = $client->request(self::REQUEST_TYPE, self::API_FUNC);

        if($response->getStatusCode() != '200') {
            return false;
        }

        // Преобразование ответа в XML-дерево
        $XML = simplexml_load_string($response->getBody()->getContents());

        $result = array();

        foreach($XML->xpath('Valute') as $exchangeRate) {
            if(in_array($exchangeRate->CharCode, $currencies)) {
                $result[(string)$exchangeRate->CharCode] = (string)$exchangeRate->Value;
            }
        }

        return $result;
    }
}
