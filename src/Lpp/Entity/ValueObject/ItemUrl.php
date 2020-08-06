<?php
declare(strict_types=1);

namespace Lpp\Lpp\Entity\ValueObject;

class ItemUrl implements ValueObjectInterface
{
    /** @var string */
    private $url;

    public function __construct(string $url)
    {
        $this->url = $this->validateUrl($url);
    }

    public function __toString(): string
    {
        return $this->url ?? 'INVALID URL';
    }

    private function validateUrl(string $url): ?string
    {
        return (preg_match(
            '/^((https?|ftp)\:\/\/)?'.
            '([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?'.
            '([a-z0-9-.]*)\.([a-z]{2,3})'.
            '(\:[0-9]{2,5})?'.
            '(\/([a-z0-9+\$_-]\.?)+)*\/?'.
            '(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?'.
            '(#[a-z_.-][a-z0-9+\$_.-]*)?/i'
        , $url)) ? $url : null;
    }
}