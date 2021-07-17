<?php
namespace Jalno\Money;

use Jalno\Money\Exception\MoneyMismatchException;

use Brick\Math\BigDecimal;
use Brick\Math\BigInteger;
use Brick\Math\BigNumber;
use Brick\Math\BigRational;
use Brick\Math\RoundingMode;
use Brick\Math\Exception\MathException;
use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use Jalno\Money\Contracts\ICurrency;

class Money extends AbstractMoney
{

    /**
     * Returns the minimum of the given monies.
     *
     * If several monies are equal to the minimum value, the first one is returned.
     *
     * @param Money    $money  The first money.
     * @param Money ...$monies The subsequent monies.
     *
     * @return Money
     *
     * @throws MoneyMismatchException If all the monies are not in the same currency.
     */
    public static function min(Money $money, Money ...$monies): Money
    {
        $min = $money;

        foreach ($monies as $money) {
            if ($money->isLessThan($min)) {
                $min = $money;
            }
        }

        return $min;
    }

    /**
     * Returns the maximum of the given monies.
     *
     * If several monies are equal to the maximum value, the first one is returned.
     *
     * @param Money    $money  The first money.
     * @param Money ...$monies The subsequent monies.
     *
     * @return Money
     *
     * @throws MoneyMismatchException If all the monies are not in the same currency.
     */
    public static function max(Money $money, Money ...$monies): Money
    {
        $max = $money;

        foreach ($monies as $money) {
            if ($money->isGreaterThan($max)) {
                $max = $money;
            }
        }

        return $max;
    }

    /**
     * Returns the total of the given monies.
     *
     * @param Money    $money  The first money.
     * @param Money ...$monies The subsequent monies.
     *
     * @return Money
     *
     * @throws MoneyMismatchException If all the monies are not in the same currency.
     */
    public static function total(Money $money, Money ...$monies): Money
    {
        $total = $money;

        foreach ($monies as $money) {
            $total = $total->plus($money);
        }

        return $total;
    }

    /**
     * Returns a Money with zero value, in the given currency.
     */
    public static function zero(ICurrency $currency): Money
    {
        $amount = BigDecimal::zero();

        return self::of($amount, $currency);
    }

    /**
     * Returns a Money of the given amount and currency.
     *
     * @param BigNumber|int|float|string $amount       The monetary amount.
     *
     * @return Money
     */
    public static function of($amount, ICurrency $currency): Money
    {
        $amount = BigDecimal::of($amount);
        return new Money($amount, $currency);
    }

    /**
     * The amount.
     *
     * @var \Brick\Math\BigDecimal
     */
    private $amount;

    /**
     * The currency.
     *
     * @var ICurrency
     */
    private $currency;

    private function __construct(BigDecimal $amount, ICurrency $currency)
    {
        $this->amount   = $amount;
        $this->currency = $currency;
    }

    /**
     * Returns the amount of this Money, as a BigDecimal.
     *
     * @return BigDecimal
     */
    public function getAmount(): BigDecimal
    {
        return $this->amount;
    }

    /**
     * Returns the amount of this Money in minor units (cents) for the currency.
     *
     * The value is returned as a BigDecimal. If this Money has a scale greater than that of the currency, the result
     * will have a non-zero scale.
     *
     * For example, `USD 1.23` will return a BigDecimal of `123`, while `USD 1.2345` will return `123.45`.
     *
     * @return BigDecimal
     */
    public function getMinorAmount(): BigDecimal
    {
        return $this->amount->withPointMovedRight($this->currency->getRoundingPrecision());
    }

    /**
     * Returns a BigInteger containing the unscaled value (all digits) of this money.
     *
     * For example, `123.4567 USD` will return a BigInteger of `1234567`.
     *
     * @return BigInteger
     */
    public function getUnscaledAmount(): BigInteger
    {
        return $this->amount->getUnscaledValue();
    }

    /**
     * Returns the Currency of this Money.
     */
    public function getCurrency(): ICurrency
    {
        return $this->currency;
    }

    /**
     * Returns the sum of this Money and the given amount.
     *
     * @param AbstractMoney|BigNumber|int|float|string $that         The money or amount to add.
     *
     * @return Money
     *
     * @throws MathException          If the argument is an invalid number or rounding is necessary.
     * @throws MoneyMismatchException If the argument is a money in a different currency or in a different.
     */
    public function plus($that): Money
    {
        $amount = $this->getAmountOf($that);

        $amount = $this->amount->toBigRational()->plus($amount);

        return self::of($amount, $this->currency);
    }

    /**
     * Returns the difference of this Money and the given amount.
     *
     * @param AbstractMoney|BigNumber|int|float|string $that         The money or amount to subtract.
     *
     * @return Money
     *
     * @throws MathException          If the argument is an invalid number or rounding is necessary.
     * @throws MoneyMismatchException If the argument is a money in a different currency.
     */
    public function minus($that): Money
    {
        $amount = $this->getAmountOf($that);
        $amount = $this->amount->toBigRational()->minus($amount);
        return self::of($amount, $this->currency);
    }

    /**
     * Returns a Money whose value is the absolute value of this Money.
     *
     * @return Money
     */
    public function abs(): Money
    {
        return new Money($this->amount->abs(), $this->currency);
    }

    /**
     * Returns a Money whose value is the negated value of this Money.
     *
     * @return Money
     */
    public function negated(): Money
    {
        return new Money($this->amount->negated(), $this->currency);
    }

    /**
     * Converts this Money to another currency, using an exchange rate.
     *
     * @param BigNumber|int|float|string $exchangeRate The exchange rate to multiply by.
     */
    public function convertedTo(ICurrency $currency, $exchangeRate): Money
    {
        $amount = $this->amount->toBigRational()->multipliedBy($exchangeRate);

        return self::of($amount, $currency);
    }

    public function __toString(): string
    {
        return $this->amount->__toString() . ' ' . $this->currency->getTitle("fa");
    }
}
