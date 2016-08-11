<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

/**
 * Основной контроллер
 *
 * @author Андрей Инихов <Andrey.Inihov@yandex.ru>
 * @version 0.1
 */
class UpdateRatesController extends Controller
{
    public function fixExchangeRates() {
        $currencies = Input::get('currencies', 'USD, EUR');

        try {
            \App\CurrencyExchangeRate::fixExchangeRates($currencies);

            return Response::json(array(
                'success' => 1,
                'reason' => '',
            ));
        }
        catch(\Exception $e) {
            return Response::json(array(
                'success' => 0,
                'reason' => $e->getMessage(),
            ));
        }
    }
}
