<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\FileSystem;

use Amp\Promise;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function Amp\File\isfile;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class File implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(static function () use ($value) {
            /** @var Result $result */
            $result = yield (new StringType())->validate($value);

            if (!$result->isValid()) {
                return $result;
            }

            if (yield isfile($value)) {
                return succeed();
            }

            return fail('FileSystem.File');
        });
    }
}
