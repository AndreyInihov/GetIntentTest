<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

/**
 * Основной контроллер
 *
 * @author Андрей Инихов <Andrey.Inihov@yandex.ru>
 * @version 0.1
 */
class MainController extends Controller
{
    public function showCurrencies() {

        return View::make('CurrenciesDefault')->render();
    }

    public function getCurrencies() {
        $latestExchangeRates = DB::select(DB::raw("select ExchangeRateValue.Currency, ExchangeRateValue.Value, ExchangeRateValue.Datetime
                                                   from `ExchangeRate` ExchangeRateValue
                                                   where ExchangeRateValue.Datetime = (select max(ExchangeRateCheck.Datetime)
                                                                                       from `ExchangeRate` ExchangeRateCheck
                                                                                       where ExchangeRateValue.Currency = ExchangeRateCheck.Currency)
        "));

        return Response::json($latestExchangeRates);
    }
}
