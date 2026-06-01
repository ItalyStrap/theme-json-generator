<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

final class Background implements ArrayableInterface, \JsonSerializable
{
    use CommonTrait;

    /**
     * @var string
     */
    public const BACKGROUND_IMAGE = 'backgroundImage';

    /**
     * @var string
     */
    public const BACKGROUND_POSITION = 'backgroundPosition';

    /**
     * @var string
     */
    public const BACKGROUND_REPEAT = 'backgroundRepeat';

    /**
     * @var string
     */
    public const BACKGROUND_SIZE = 'backgroundSize';

    /**
     * @var string
     */
    public const BACKGROUND_ATTACHMENT = 'backgroundAttachment';

    public function backgroundImage(string $value): self
    {
        return $this->setProperty(self::BACKGROUND_IMAGE, $value);
    }

    public function backgroundPosition(string $value): self
    {
        return $this->setProperty(self::BACKGROUND_POSITION, $value);
    }

    public function backgroundRepeat(string $value): self
    {
        return $this->setProperty(self::BACKGROUND_REPEAT, $value);
    }

    public function backgroundSize(string $value): self
    {
        return $this->setProperty(self::BACKGROUND_SIZE, $value);
    }

    public function backgroundAttachment(string $value): self
    {
        return $this->setProperty(self::BACKGROUND_ATTACHMENT, $value);
    }
}
