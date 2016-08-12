<?php

namespace App\Providers;

use App\Providers\ExchangeRateProviderInterface as ExchnageRateProviderInterface;
use App\Providers\ExchangeRateProviderHelper as ExchangeRateProviderHelper;

use GuzzleHttp\Client;

class YahooExchangeRateProvider extends ExchangeRateProviderHelper implements ExchnageRateProviderInterface
{
    // Адрес сайта ЦБ
    const BASE_URL = "http://query.yahooapis.com";

    // Адрес вызываемой функции
    const API_FUNC = "/v1/public/yql";

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

        $currenciesString = '';

        foreach($currencies as $currency) {
            $currenciesString .= $currrency . 'RUB,';
        }

        if(strlen($currenciesString) > 0) {
            $currenciesString = substr($currenciesString, 0, strlen($currenciesString)-1);
        }

        $response = $client->request(self::REQUEST_TYPE, self::API_FUNC, array(
                'query' => array(
                    'q' => 'select * from yahoo.finance.xchange where pair = "{' . $currenciesString . '}"',
                    'format' => 'json',
                    'env' => 'store://datatables.org/alltableswithkeys',
                ),
            )
        );

        if($response->getStatusCode() != '200') {
            return false;
        }

        $rates = json_decode($response->getBody()->getContents());

        $result = array();

        foreach($rates->query->results->rate as $exchangeRate) {
            $currentCurrency = explode('/', $exchangeRate->Name)[0];

            if(in_array($currentCurrency, $currencies)) {
                $result[$currentCurrency] = $exchangeRate->Rate;
            }
        }

        return $result;
    }
}
