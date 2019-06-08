<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 14.08.2018
 * Time: 14:54
 */

namespace App\Component\Menu;


/**
 * Class Icon
 * @package App\Component\Menu
 */
class Icon
{
    /**
     * Icon string, e.g. "fas fa-users" (without quotes)
     * @var string
     */
    protected $icon;

    /**
     * Icon constructor.
     * @param string $icon
     */
    public function __construct(string $icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return Icon
     */
    public function setIcon(string $icon): Icon
    {
        $this->icon = $icon;
        return $this;
    }
}
