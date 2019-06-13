<?php


namespace App\Util;


/**
 * Class HtmlTitle
 * @package App\Util
 */
class HtmlTitle
{
    /**
     * HtmlTitle constructor.
     */
    public function __construct()
    {
    }

    /**
     *
     */
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }

    /**
     *
     */
    const DEFAULT_SEPARATOR = '&bull;';

    /**
     *
     */
    const DEFAULT_TITLE = 'welcome!';

    /**
     * @var array
     */
    private $parts = [self::DEFAULT_TITLE];

    /**
     * @var string
     */
    private $separator = self::DEFAULT_SEPARATOR;

    /**
     * @return array
     */
    public function getParts(): array
    {
        return $this->parts;
    }

    /**
     * @param array $parts
     * @return HtmlTitle
     */
    public function setParts(array $parts): HtmlTitle
    {
        $this->parts = $parts;
        return $this;
    }

    /**
     * @param string $title
     * @return HtmlTitle
     */
    public function setTitle(string $title): HtmlTitle
    {
        $this->parts = [$title];
        return $this;
    }

    /**
     * @param string $part
     * @return HtmlTitle
     */
    public function appendPart(string $part): HtmlTitle
    {
        $this->parts[] = $part;
        return $this;
    }

    /**
     * @param string $part
     * @return HtmlTitle
     */
    public function prependPart(string $part): HtmlTitle
    {
        array_unshift($this->parts, $part);
        return $this;
    }

    /**
     * @return string
     */
    public function getSeparator(): string
    {
        return $this->separator;
    }

    /**
     * @param string $separator
     * @return HtmlTitle
     */
    public function setSeparator(string $separator): HtmlTitle
    {
        $this->separator = $separator;
        return $this;
    }

    /**
     * @return HtmlTitle
     */
    public function resetSeparator(): HtmlTitle
    {
        $this->separator = static::DEFAULT_SEPARATOR;
        return $this;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return join($this->separator, $this->parts);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
