<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CurrencyExchangeRate extends Model
{
    protected $table = 'ExchangeRate';
    protected $primaryKey = 'ID';

    public static function fixExchangeRates($currencies = 'USD, EUR') {
        $Yahoo = new \App\Providers\YahooExchangeRateProvider();

        //  Пробуем получить данные с Yahoo
        $rates = $Yahoo->getRateValues($currencies);

        if($rates) {
            foreach($rates['rates'] as $currency => $exchangeRate) {
                $rateRecord = new self();
                $rateRecord->Datetime = \Carbon\Carbon::createFromFormat('Ymd H:i:s', $rates['date'])->toDateTimeString();
                $rateRecord->Currency = $currency;
                $rateRecord->Value = $exchangeRate;

                if(!$rateRecord->save()) {
                    throw new \Exception('Ошибка при записи в базу');
                }
            }

            return true;
        }
        else {
            // Если получить данные с Yahoo не получилось, делаем запрос к ЦБ
            $CB = new \App\Providers\CBExchangeRateProvider();

            $rates = $CB->getRateValues($currencies);

            if($rates) {
                foreach($rates['rates'] as $currency => $exchangeRate) {
                    $rateRecord = new self();
                    $rateRecord->Datetime = \Carbon\Carbon::createFromFormat('Ymd H:i:s', $rates['date'])->toDateTimeString();
                    $rateRecord->Currency = $currency;
                    $rateRecord->Value = $exchangeRate;
                    
                    if(!$rateRecord->save()) {
                        throw new \Exception('Ошибка при записи в базу');
                    }
                }
            }

            return true;
        }

        return false;
    }
}
