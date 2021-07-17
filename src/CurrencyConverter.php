<?php
namespace Jalno\Money;

use Jalno\Money\Contracts\ICurrency;
use Jalno\Money\Contracts\IExchangeRateProvider;
use Jalno\Money\Exception\CurrencyConversionException;

use Brick\Math\BigRational;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Math\RoundingMode;

/**
 * Converts monies into different currencies, using an exchange rate provider.
 *
 * @todo Now that this class provides methods to convert to both Money and RationalMoney, it makes little sense to
 *       provide the context in the constructor, as this only applies to convert() and not convertToRational().
 *       This should probably be a parameter to convert().
 */
class CurrencyConverter
{
    /**
     * @var IExchangeRateProvider
     */
    private $exchangeRateProvider;

    public function __construct(IExchangeRateProvider $exchangeRateProvider)
    {
        $this->exchangeRateProvider = $exchangeRateProvider;
    }

    /**
     * Converts the given money to the given currency.
     * @throws CurrencyConversionException If the exchange rate is not available.
     */
    public function convert(AbstractMoney $money, ICurrency $currency, ?int $roundingMode = null) : Money
    {
        $exchangeRate = $this->exchangeRateProvider->getExchangeRate(
            $money->getCurrency(),
            $currency
        );

        return $money->convertedTo($currency, $exchangeRate);
    }
}
