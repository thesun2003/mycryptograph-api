<?php
// Routes

namespace App;

use api\PoloniexAPI;

class ApiController
{
    // TODO: move to an appropriate place
    const BTC_VALUE = 'btcValue';
    const USD_VALUE = 'usdValue';
    const TOKEN_VALUE = 'tokenValue';

    protected $container;
    protected $db = [];

    protected $db_filename = __DIR__ . '/../../db/database.txt';
    protected $grabdataURL = 'https://driveelectric.org.nz/';

    public function __construct(\Slim\Container $container)
    {
        $this->container = $container;
    }

    private function _getPoloniexAPI() {
        $settings = $this->container->get('settings');

        if (!isset($settings['api'], $settings['api']['poloniex'])) {
            throw \Exception('API settings for Poloniex does not exist');
        }

        $poloniex_settings = $settings['api']['poloniex'];
        $api = new PoloniexAPI(
            $poloniex_settings['public_key'],
            $poloniex_settings['private_key']
        );

        return $api;
    }

    private function getTotals($balances) {
        $data = [
            self::BTC_VALUE => 0.0,
            self::USD_VALUE => 0.0,
        ];

        foreach ($balances as $currency => $balance) {
            $data[self::BTC_VALUE] += $balance[self::BTC_VALUE];
            $data[self::USD_VALUE] += $balance[self::USD_VALUE];
        }

        return $data;
    }

    private function getPercentage($piece, $total) {
        return (floatval($piece) / floatval($total)) * 100;
    }

    private function updateBalancesData($balances, $totals) {
        foreach ($balances as $currency => &$balance) {
            $balance['proportion'] = $this->getPercentage($balance[self::BTC_VALUE], $totals[self::BTC_VALUE]);
        }

        return $balances;
    }

    public function getTotalBalances($request, $response, $args)
    {        
        $api = $this->_getPoloniexAPI();

        $totalBalances = $api->get_total_balances();
        $totals = $this->getTotals($totalBalances);

        $data = [
            'currencies' => $this->updateBalancesData($totalBalances, $totals),
            'totals' => $totals,
        ];

        return $response->withJson($data);
    }


    public function getExchangeRates($request, $response, $args) {
        $api = $this->_getPoloniexAPI();

        $data = $api->get_ticker('USDT_BTC');
        return $response->withJson($data);
    }

}

$app->get('/', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->get('/get-total-balances/', ApiController::class . ':getTotalBalances');
$app->get('/get-rates/', ApiController::class . ':getExchangeRates');
