# mycryptograph-api
API for the MyCryptoGraph project

MVP:
    /get-total-balances/

    Use Poloniex API for that:
    * public API request - returnTicker (https://poloniex.com/public?command=returnTicker, check USD_BTC)
    * private API reqest - returnCompleteBalances

Need to add:
    * Replace CURL requests with Requests library
    * Add better error handling
    * Add response caching

API:
    PHP (Slim 3.1, rmccue/Requests 1.7.0)


