<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

use HarmonyIO\Validation\Rule\BankAccount\Iban\Country;

final class Andorra extends Country
{
    private const PATTERN = '~^AD\d{2}\d{4}\d{4}[a–zA-Z0-9]{12}$~';

    public function __construct()
    {
        parent::__construct(self::PATTERN, 'andorra');
    }
}
