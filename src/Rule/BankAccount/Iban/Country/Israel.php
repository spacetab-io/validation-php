<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Israel extends Country
{
    private const PATTERN = '~^IL\d{2}\d{3}\d{3}\d{13}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'israel');
    }
}
