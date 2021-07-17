<?php
namespace Jalno\Money\Exception;

use Jalno\Money\Contracts\ICurrency;

/**
 * Exception thrown when an exchange rate is not available.
 */
class CurrencyConversionException extends MoneyException
{
    /**
     * @var ICurrency $sourceCurrency
     */
    private $sourceCurrency;

    /**
     * @var ICurrency $targetCurrency
     */
    private $targetCurrency;

    /**
     * CurrencyConversionException constructor.
     *
     * @param string $message
     * @param ICurrency $sourceCurrency
     * @param ICurrency $targetCurrency
     */
    public function __construct(string $message, ICurrency $sourceCurrency, ICurrency $targetCurrency)
    {
        parent::__construct($message);

        $this->sourceCurrency = $sourceCurrency;
        $this->targetCurrency = $targetCurrency;
    }

    public function getSourceCurrency() : ICurrency
    {
        return $this->sourceCurrency;
    }

    public function getTargetCurrency() : ICurrency
    {
        return $this->targetCurrency;
    }
}
