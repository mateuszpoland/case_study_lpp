<?php
declare(strict_types=1);

namespace Lpp\Test\Lpp\Entity\ValueObject;

use Lpp\Lpp\Entity\ValueObject\ItemUrl;
use PHPUnit\Framework\TestCase;

class ItemUrlTest extends TestCase
{
    /** @var ItemUrl */
    private $ItemUrl;

    public function correctUrls(): array
    {
        return [
            [
                [
                    'http://example.com',
                    'http://www.anotherexample.com',
                    'www.google.pl',
                    'www.reserved.pl/categories',
                ]
            ]
        ];
    }

    public function incorrectUrls(): array
    {
        return [
            [
                [
                    'htt1p://example.com',
                    'htt://www.anotherexample.',
                    'www./&google..pl',
                    'ftp://path/subpath/'
                ]
            ]
        ];
    }

    /** @dataProvider correctUrls */
    public function testSavesUrlAsValid(array $urls): void
    {
        foreach ($urls as $url) {
            $ItemUrl = new ItemUrl($url);
            $this->assertSame($url, $ItemUrl->__toString());
        }
    }

    /** @dataProvider incorrectUrls */
    public function testInvalidUrl(array $urls): void
    {
        foreach($urls as $url) {
            $ItemUrl = new ItemUrl($url);
            $this->assertSame('INVALID URL', $ItemUrl->__toString());
        }
    }
}