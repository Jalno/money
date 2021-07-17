<?php
namespace Jalno\Money\Contracts;

use Brick\Math\BigNumber;
use Brick\Math\RoundingMode;
use Jalno\Money\Contracts\ICurrency\Expression;

interface ICurrency
{
	/**
	 * The ID of the currency and must be unique across project.
	 *
	 * @return positive-int that should be unsinged big integer
	 */
	public function getID(): int;

	/**
	 * Set the ID of the currency
	 *
	 * @param positive-int $id that should be unsinged big integer
	 */
	public function setID(int $id): void;

	/**
	 * Get the currency code.
	 * This code will be the 3-letter uppercase ISO 4217 currency code.
	 */
	public function getCode(): string;

	/**
	 * Set the currency code.
	 * This code should be the 3-letter uppercase ISO 4217 currency code.
	 */
	public function setCode(string $code): void;

	/**
	 * Get the prefix for language and locale
	 */
	public function getPrefix(string $lang, ?string $locale = null): ?Expression;

	/**
	 * @return Expression[]
	 */
	public function getPrefixes(): array;

	/**
	 * Set the prefix
	 */
	public function setPrefix(Expression $prefix): void;

	/**
	 * Get title of the currency based on language and locale
	 *
	 * @param string $lang that is in ISO 639-1 format, ex: en, fr, ...
	 * @param string|null $locale that is the locale of the lang in ISO ISO 3166_1_alpha2 format, ex: US, UK
	 */
	public function getTitle(string $lang, ?string $locale = null): ?string;

	/**
	 * Set the title of the currency
	 */
	public function setTitle(Expression $title): void;

	/**
	 * Get prefix of the 
	 *
	 * @param string $lang that is in ISO 639-1 format, ex: en, fr, ...
	 * @param string|null $locale that is the locale of the lang in ISO ISO 3166_1_alpha2 format, ex: US, UK
	 */
	public function getPostfix(string $lang, ?string $locale = null): ?Expression;

	/**
	 * @return Expression[]
	 */
	public function getPostfixes(): array;

	/**
	 * Set the prefix
	 *
	 * @param Expression $postfix that contain language and locale, note if this lang and locale is exists, it will be overwritten
	 */
	public function setPostfix(Expression $postfix): void;

	/**
	 * @return RoundingMode::* that is rounding mode of this currency
	 */
	public function getRoundingMode(): int;

	/**
	 * @param RoundingMode::* $mode
	 * @throws \InvalidArgumentException in case the mode is not valid.
	 */
	public function setRoundingMode(int $mode): void;

	/**
     * Returns the default number of fraction digits (typical scale) used with this currency.
     *
     * For example, the default number of fraction digits for the Euro is 2, while for the Japanese Yen it is 0.
     *
     * @return int
     */
	public function getRoundingPrecision(): int;

	/**
	 * Set the rounding precision of the currency
	 *
	 * @param int $precision
	 */
	public function setRoundingPrecision(int $precision): void;

}
