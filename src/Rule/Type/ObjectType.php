<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Type;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;

final class ObjectType implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return new Success(is_object($value));
    }
}
