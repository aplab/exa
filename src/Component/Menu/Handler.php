<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 14.08.2018
 * Time: 14:50
 */

namespace App\Component\Menu;


class Handler extends Action
{
    /**
     * @var string
     */
    private $handler;

    /**
     * Handler constructor.
     * @param string $handler
     */
    public function __construct(string $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @return string
     */
    public function getHandler(): string
    {
        return $this->handler;
    }

    /**
     * @param string $handler
     * @return Handler
     */
    public function setHandler(string $handler): Handler
    {
        $this->handler = $handler;
        return $this;
    }
}
