<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\File;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\File\MimeType;
use HarmonyIO\ValidationTest\Unit\Rule\FileTestCase;

class MimeTypeTest extends FileTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName, MimeType::class, 'text/plain');
    }

    public function testValidateFailsWhenNotMatchingMimeType()
    {
        /** @var Result $result */
        $result = yield (new MimeType('application/json'))->validate(TEST_DATA_DIR . '/file-mimetype-test.txt');

        $this->assertFalse($result->isValid());
        $this->assertSame('File.MimeType', $result->getFirstError()->getMessage());
        $this->assertSame('mimeType', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame('application/json', $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateSucceedsWhenFileMatchesMimeType()
    {
        /** @var Result $result */
        $result = yield (new MimeType('text/plain'))->validate(TEST_DATA_DIR . '/file-mimetype-test.txt');

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
