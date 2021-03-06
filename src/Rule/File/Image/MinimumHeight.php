<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\File\Image;

use Amp\Promise;
use HarmonyIO\Validation\Result\Parameter;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function Amp\ParallelFunctions\parallel;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class MinimumHeight implements Rule
{
    /** @var int */
    private $minimumHeight;

    public function __construct(int $minimumHeight)
    {
        $this->minimumHeight = $minimumHeight;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(function () use ($value) {
            /** @var Result $result */
            $result = yield (new Image())->validate($value);

            if (!$result->isValid()) {
                return $result;
            }

            return parallel(function () use ($value) {
                // @codeCoverageIgnoreStart
                $imageSizeInformation = @getimagesize($value);

                if (!$imageSizeInformation || $imageSizeInformation[1] < $this->minimumHeight) {
                    return fail(
                        'File.Image.MinimumHeight',
                        new Parameter('height', $this->minimumHeight)
                    );
                }

                return succeed();
                // @codeCoverageIgnoreEnd
            })();
        });
    }
}
