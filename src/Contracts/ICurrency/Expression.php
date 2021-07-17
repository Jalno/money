<?php
namespace Jalno\Money\Contracts\ICurrency;

class Expression
{
	protected string $value;
	protected string $language;
	protected ?string $locale;

	public function __construct(string $value, string $language, ?string $locale = null)
	{
		$this->value = $value;
		$this->language = $language;
		$this->locale = $locale;
	}

	public function getValue(): string
	{
		return $this->value;
	}

	public function getLanguage(): string
	{
		return $this->language;
	}

	public function getLocale(): ?string
	{
		return $this->locale;
	}
}
