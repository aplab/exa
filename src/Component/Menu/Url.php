<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 14.08.2018
 * Time: 14:52
 */

namespace Aplab\AplabAdminBundle\Component\Menu;


class Url extends Action
{
    /**
     * @var string
     */
    private $url;

    /**
     * Url constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Url
     */
    public function setUrl(string $url): Url
    {
        $this->url = $url;
        return $this;
    }
}