<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/*
 * Провайдер, содержащий основные функции для получения информации об обменных курсах валют
 */
class ExchangeRateProviderHelper extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register() {

    }

    public function __construct() {

    }

    /**
    * @var array|string $currencies Массив из трехзначных кодов или строка с перечислением
    * @return array
    *
    * Метод должен возвращать массив следующего вида
    * [ 'date' => '2016­03­02 10:23:59', 'rates' => [ 'USD' => 73.0865, 'EUR' => 80.1231 ]]
    *
    * Курсы валют в ноде rates должны быть для тех валют,
    * которые переданы в параметре $currencies через запятую или массивом
    */
    public function getRateValues($currencies = 'USD, EUR') {
        $currentTime = date('Ymd H:i:s');

        if(!is_array($currencies)) {
            $currencies = self::parseCurrenciesString($currencies);
        }

        if(empty($currencies)) {
            throw new \Exception('Переданы некорректные коды валют');
        }

        return array(
            'date' => $currentTime,
            'rates' => $this->requestRates($currencies),
        );
    }

    /**
    * Метод преобразует строку, содержащую коды валют в массив
    *
    * @var string $currencies Строка с перечислением трехзначных кодов валют
    *
    * @return array
    */
    public function parseCurrenciesString($currencies) {
        $currenciesList = explode(', ', $currencies);

        if($currenciesList[0] == '') {
            return array();
        }
        else {
            return $currenciesList;
        }
    }
}
