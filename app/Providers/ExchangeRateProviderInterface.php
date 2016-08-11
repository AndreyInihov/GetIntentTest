<?php

namespace App\Providers;

interface ExchangeRateProviderInterface
{
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
    public function getRateValues($currencies);
}
