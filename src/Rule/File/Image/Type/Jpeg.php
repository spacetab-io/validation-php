<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\File\Image\Type;

use Amp\Promise;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\File\MimeType;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function Amp\ParallelFunctions\parallel;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class Jpeg implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(static function () use ($value) {
            /** @var Result $result */
            $result = yield (new MimeType('image/jpeg'))->validate($value);

            if (!$result->isValid()) {
                return $result;
            }

            return parallel(static function () use ($value) {
                // @codeCoverageIgnoreStart
                $image = @imagecreatefromjpeg($value);

                if ($image !== false) {
                    return succeed();
                }

                return fail('File.Image.Type.Jpeg');
                // @codeCoverageIgnoreEnd
            })();
        });
    }
}
