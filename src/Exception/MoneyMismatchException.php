<?php
declare(strict_types=1);

namespace Jalno\Money\Exception;

use Jalno\Money\Contracts\ICurrency;

/**
 * Exception thrown when a money is not in the expected currency.
 */
class MoneyMismatchException extends MoneyException
{

    protected ICurrency $expected;

    protected ICurrency $actual;

    public function __construct(string $message, ICurrency $expected, ICurrency $actual)
    {
        parent::__construct($message);
        $this->expected = $expected;
        $this->actual = $actual;
    }

    public function getExceptedCurrency(): ICurrency
    {
        return $this->expected;
    }

    public function getActualCurrency(): ICurrency
    {
        return $this->actual;
    }
}
