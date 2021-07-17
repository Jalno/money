<?php
declare(strict_types=1);

namespace Jalno\Money\Exception;

use Jalno\Money\Contracts\ICurrency;
use Jalno\Money\Contracts\ICurrencyRepository;

/**
 * Exception thrown when can not save currency in currency repository.
 */
class CurrencyRepositorySaveException extends MoneyException
{
    /**
     * The currency repository that is tried to save currency.
     *
     * @property ICurrencyRepository $currencyRepository
     */
    private ICurrencyRepository $currencyRepository;

    /**
     * The currency that repository failed to save it.
     *
     * @property ICurrency $currency
     */
    private ICurrency $currency;

    /**
     * CurrencyRepositorySaveException constructor.
     *
     * @param string $message
     * @param ICurrencyRepository $currencyRepository
     * @param ICurrency $currency
     */
    public function __construct(string $message, ICurrencyRepository $currencyRepository, ICurrency $currency)
    {
        parent::__construct($message);

        $this->currencyRepository = $currencyRepository;
        $this->currency = $currency;
    }

    public function getCurrencyRepository(): ICurrencyRepository
    {
        return $this->currencyRepository;
    }

    public function getCurrency(): ICurrency
    {
        return $this->currency;
    }
}
