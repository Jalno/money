<?php
namespace Jalno\Money\Contracts;

use Brick\Math\BigNumber;
use Jalno\Money\Exception\CurrencyConversionException;

/**
 * Interface for exchange rate providers.
 */
interface IExchangeRateProvider
{
    /**
     * @param ICurrency $sourceCurrencyCode The source currency code.
     * @param ICurrency $targetCurrencyCode The target currency code.
     *
     * @return BigNumber|int|float|string The exchange rate.
     *
     * @throws CurrencyConversionException If the exchange rate is not available.
     */
    public function getExchangeRate(ICurrency $sourceCurrencyCode, ICurrency $targetCurrencyCode);
}
