<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 14.08.2018
 * Time: 14:52
 */

namespace App\Component\Toolbar;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class Route
 * @package App\Component\Menu
 */
class Route extends Action
{
    /**
     * @var string
     */
    private $route;

    /**
     * @var array
     */
    private $parameters;

    /**
     * Route constructor.
     * @param string $route
     * @param array $parameters
     */
    public function __construct(string $route, array $parameters = [])
    {
        $this->route = $route;
        $this->parameters = $parameters;
    }

    /**
     * @return UrlGeneratorInterface
     */
    public static function getRouter(): UrlGeneratorInterface
    {
        return self::$router;
    }

    /**
     * @param UrlGeneratorInterface $router
     */
    public static function setRouter(UrlGeneratorInterface $router): void
    {
        self::$router = $router;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @param string $route
     * @return Route
     */
    public function setRoute(string $route): Route
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     * @return Route
     */
    public function setParameters(array $parameters): Route
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @return string
     */
    public function generateUrl()
    {
        return static::getRouter()->generate(
            $this->getRoute(),
            $this->getParameters()
        );
    }

    /**
     * @var UrlGeneratorInterface
     */
    protected static $router;
}
