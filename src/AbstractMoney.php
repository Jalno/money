<?php
namespace Jalno\Money;

use Jalno\Money\Contracts\ICurrency;
use Jalno\Money\Exception\MoneyMismatchException;

use Brick\Math\BigNumber;
use Brick\Math\RoundingMode;
use Brick\Math\Exception\MathException;

abstract class AbstractMoney
{

    abstract public function getAmount(): BigNumber;

    abstract public function getCurrency(): ICurrency;

    /**
     * @param BigNumber|int|float|string $exchangeRate The exchange rate to multiply by.
     */
    abstract public function convertedTo(ICurrency $currency, $exchangeRate): Money;

    /**
     * Returns the sign of this money.
     *
     * @return int -1 if the number is negative, 0 if zero, 1 if positive.
     */
    final public function getSign() : int
    {
        return $this->getAmount()->getSign();
    }

    final public function isZero() : bool
    {
        return $this->getAmount()->isZero();
    }

    final public function isNegative() : bool
    {
        return $this->getAmount()->isNegative();
    }

    final public function isNegativeOrZero() : bool
    {
        return $this->getAmount()->isNegativeOrZero();
    }

    final public function isPositive() : bool
    {
        return $this->getAmount()->isPositive();
    }

    final public function isPositiveOrZero() : bool
    {
        return $this->getAmount()->isPositiveOrZero();
    }

    /**
     * Compares this money to the given amount.
     *
     * @param AbstractMoney|BigNumber|int|float|string $that
     *
     * @return int [-1, 0, 1] if `$this` is less than, equal to, or greater than `$that`.
     *
     * @throws MathException          If the argument is an invalid number.
     * @throws MoneyMismatchException If the argument is a money in a different currency.
     */
    final public function compareTo($that) : int
    {
        return $this->getAmount()->compareTo($this->getAmountOf($that));
    }

    /**
     * Returns whether this money is equal to the given amount.
     *
     * @param AbstractMoney|BigNumber|int|float|string $that
     *
     * @return bool
     *
     * @throws MathException          If the argument is an invalid number.
     * @throws MoneyMismatchException If the argument is a money in a different currency.
     */
    final public function isEqualTo($that) : bool
    {
        return $this->getAmount()->isEqualTo($this->getAmountOf($that));
    }

    /**
     * Returns whether this money is less than the given amount.
     *
     * @param AbstractMoney|BigNumber|int|float|string $that
     *
     * @return bool
     *
     * @throws MathException          If the argument is an invalid number.
     * @throws MoneyMismatchException If the argument is a money in a different currency.
     */
    final public function isLessThan($that) : bool
    {
        return $this->getAmount()->isLessThan($this->getAmountOf($that));
    }

    /**
     * Returns whether this money is less than or equal to the given amount.
     *
     * @param AbstractMoney|BigNumber|int|float|string $that
     *
     * @return bool
     *
     * @throws MathException          If the argument is an invalid number.
     * @throws MoneyMismatchException If the argument is a money in a different currency.
     */
    final public function isLessThanOrEqualTo($that) : bool
    {
        return $this->getAmount()->isLessThanOrEqualTo($this->getAmountOf($that));
    }

    /**
     * Returns whether this money is greater than the given amount.
     *
     * @param AbstractMoney|BigNumber|int|float|string $that
     *
     * @return bool
     *
     * @throws MathException          If the argument is an invalid number.
     * @throws MoneyMismatchException If the argument is a money in a different currency.
     */
    final public function isGreaterThan($that) : bool
    {
        return $this->getAmount()->isGreaterThan($this->getAmountOf($that));
    }

    /**
     * Returns whether this money is greater than or equal to the given amount.
     *
     * @param AbstractMoney|BigNumber|int|float|string $that
     *
     * @return bool
     *
     * @throws MathException          If the argument is an invalid number.
     * @throws MoneyMismatchException If the argument is a money in a different currency.
     */
    final public function isGreaterThanOrEqualTo($that) : bool
    {
        return $this->getAmount()->isGreaterThanOrEqualTo($this->getAmountOf($that));
    }

    /**
     * Returns the amount of the given parameter.
     *
     * If the parameter is a money, its currency is checked against this money's currency.
     *
     * @param AbstractMoney|BigNumber|int|float|string $that A money or amount.
     *
     * @return BigNumber|int|float|string
     *
     * @throws MoneyMismatchException If currencies don't match.
     */
    final protected function getAmountOf($that)
    {
        if ($that instanceof AbstractMoney) {
            if ($that->getCurrency()->getID() != $this->getCurrency()->getID())
            {
                throw new MoneyMismatchException(
                    "The currencies is not same!",
                    $this->getCurrency(),
                    $that->getCurrency()
                );
            }

            return $that->getAmount();
        }

        return $that;
    }
}
