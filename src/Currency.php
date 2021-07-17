<?php
namespace Jalno\Money;

use Brick\Math\BigNumber;
use Brick\Math\RoundingMode;
use Jalno\Money\Contracts\ICurrency;
use Jalno\Money\Contracts\ICurrency\Expression;

class Currency implements ICurrency
{
	/**
	 * @var positive-int $id
	 */
	protected int $id;

	protected string $code;

	protected int $mode;

	protected int $precision;

	/**
	 * @var array<string, Expression[]> $titles
	 */
	protected $titles = array(

	);

	/**
	 * @var array<string, Expression[]> $prefixes
	 */
	protected $prefixes = array(

	);

	/**
	 * @var array<string, Expression[]> $postfixes
	 */
	protected $postfixes = array(

	);

	/**
	 * @param positive-int $id
	 */
	public function __construct(int $id, string $code, int $mode, int $precision)
	{
		$this->setID($id);
		$this->setCode($code);
		$this->setRoundingMode($mode);
		$this->setRoundingPrecision($precision);
	}

	/**
	 * The ID of the currency and must be unique across project.
	 *
	 * @return positive-int that should be unsinged big integer
	 */
	public function getID(): int
	{
		return $this->id;
	}

	/**
	 * Set the ID of the currency
	 *
	 * @param positive-int $id that should be unsinged big integer
	 */
	public function setID(int $id): void
	{
		$this->id = $id;
	}

	/**
	 * Get the currency code.
	 * This code will be the 3-letter uppercase ISO 4217 currency code.
	 */
	public function getCode(): string
	{
		return $this->code;
	}

	/**
	 * Set the currency code.
	 * This code should be the 3-letter uppercase ISO 4217 currency code.
	 */
	public function setCode(string $code): void
	{
		$this->code = $code;
	}

	/**
	 * Get the prefix for language and locale
	 */
	public function getPrefix(string $lang, ?string $locale = null): ?Expression
	{
		if (isset($this->prefixes[$lang]))
		{
			if ($locale)
			{
				foreach ($this->prefixes[$lang] as $item)
				{
					if ($item->getLocale() == $locale)
					{
						return $item;
					}
				}
			} else
			{
				return $this->prefixes[$lang][0] ?? null;
			}
		}
		return null;
	}

	/**
	 * @return Expression[]
	 */
	public function getPrefixes(): array
	{
		return array_merge(...$this->prefixes);
	}

	/**
	 * Set the prefix
	 */
	public function setPrefix(Expression $prefix): void
	{
		$lang = $prefix->getLanguage();
		if (!isset($this->prefixes[$lang]))
		{
			$this->prefixes[$lang] = array();
		}
		if ($this->prefixes[$lang])
		{
			foreach ($this->prefixes[$lang] as $key => $item)
			{
				if ($item->getLocale() === $prefix->getLocale())
				{
					unset($this->prefixes[$lang][$key]);
				}
			}
		}
		$this->prefixes[$lang][] = $prefix;
	}

	/**
	 * Get title of the currency based on language and locale
	 *
	 * @param string $lang that is in ISO 639-1 format, ex: en, fr, ...
	 * @param string|null $locale that is the locale of the lang in ISO ISO 3166_1_alpha2 format, ex: US, UK
	 */
	public function getTitle(string $lang, ?string $locale = null): ?Expression
	{
		$byLang = $this->titles[$lang] ?? null;
		if ($byLang)
		{
			if ($locale)
			{
				foreach ($byLang as $item)
				{
					if ($item->getLocale() === $locale)
					{
						return $item;
					}
				}
				return null;
			}
			return $byLang[0] ?? null;
		}
		return null;
	}

	/**
	 * Set the title of the currency
	 */
	public function setTitle(Expression $title): void
	{
		$lang = $title->getLanguage();
		if (!isset($this->titles[$lang]))
		{
			$this->titles[$lang] = array();
		}
		if ($this->titles[$lang])
		{
			foreach ($this->titles[$lang] as $key => $item)
			{
				if ($item->getLocale() === $title->getLocale())
				{
					unset($this->titles[$lang][$key]);
				}
			}
		}
		$this->titles[$lang][] = $title;
	}

	/**
	 * Get prefix of the 
	 *
	 * @param string $lang that is in ISO 639-1 format, ex: en, fr, ...
	 * @param string|null $locale that is the locale of the lang in ISO ISO 3166_1_alpha2 format, ex: US, UK
	 */
	public function getPostfix(string $lang, ?string $locale = null): ?Expression
	{
		if (isset($this->postfixes[$lang]))
		{
			if ($locale)
			{
				foreach ($this->postfixes[$lang] as $item)
				{
					if ($item->getLocale() == $locale)
					{
						return $item;
					}
				}
			} else
			{
				return $this->postfixes[$lang][0] ?? null;
			}
		}
		return null;
	}

	/**
	 * @return Expression[]
	 */
	public function getPostfixes(): array
	{
		return array_merge(...$this->postfixes);
	}

	/**
	 * Set the prefix
	 *
	 * @param Expression $postfix that contain language and locale, note if this lang and locale is exists, it will be overwritten
	 */
	public function setPostfix(Expression $postfix): void
	{
		$lang = $postfix->getLanguage();
		if (!isset($this->postfixes[$lang]))
		{
			$this->postfixes[$lang] = array();
		}
		if ($this->postfixes[$lang])
		{
			foreach ($this->postfixes[$lang] as $key => $item)
			{
				if ($item->getLocale() === $postfix->getLocale())
				{
					unset($this->postfixes[$lang][$key]);
				}
			}
		}
		$this->postfixes[$lang][] = $postfix;
	}

	/**
	 * @return RoundingMode::* that is rounding mode of this currency
	 */
	public function getRoundingMode(): int
	{
		return $this->mode;
	}

	/**
	 * @param RoundingMode::* $mode
	 * @throws \InvalidArgumentException in case the mode is not valid.
	 */
	public function setRoundingMode(int $mode): void
	{
		if (!in_array(
			$mode,
			(new \ReflectionClass(RoundingMode::class))->getConstants()
		))
		{
			throw new \InvalidArgumentException();
		}
		$this->mode = $mode;
	}

	/**
     * Returns the default number of fraction digits (typical scale) used with this currency.
     *
     * For example, the default number of fraction digits for the Euro is 2, while for the Japanese Yen it is 0.
     *
     * @return int
     */
	public function getRoundingPrecision(): int
	{
		return $this->precision;
	}

	/**
	 * Set the rounding precision of the currency
	 *
	 * @param int $precision
	 */
	public function setRoundingPrecision(int $precision): void
	{
		if ($precision < 0)
		{
			throw new \InvalidArgumentException();
		}
		$this->precision = $precision;
	}

}
