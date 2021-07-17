<?php
namespace Jalno\Money\Contracts;

use Jalno\Money\Exception\CurrencyRepositorySaveException;

interface ICurrencyRepository
{
	/**
	 * Find a currency by it's ID.
	 *
	 * @param positive-int $id that is id of the currency you are looking for.
	 */
	public function getByID(int $id): ?ICurrency;

	/**
	 * Get All currencies with ISO 4217 currency code.
	 *
	 * @param string $code 3-letter uppercase ISO 4217 currency code.
	 * @return ICurrency[]
	 */
	public function byCode(string $code): array;

	/**
	 *
	 * @throws CurrencyRepositorySaveException in case of failure.
	 */
	public function save(ICurrency $currency): void;
}
