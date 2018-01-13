<?php
// Routes

namespace App;

use api\PoloniexAPI;

class ApiController
{
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

    public function getTotalBalances($request, $response, $args)
    {        
        $api = $this->_getPoloniexAPI();

        $data = $api->get_total_balances();
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
