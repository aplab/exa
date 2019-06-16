<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 22.08.2018
 * Time: 15:19
 */

namespace App\Component\Toolbar;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class ToolbarManager
 * @package App\Component\Toolbar
 */
class ToolbarManager
{
    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * ToolbarManager constructor.
     * @param UrlGeneratorInterface $router
     */
    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
        Route::setRouter($router);
    }

    /**
     * @var string
     */
    const DEFAULT_INSTANCE_NAME = 'apl-admin-toolbar';

    /**
     * @var Toolbar[]
     */
    private $instances = [];

    /**
     * @param string $id
     * @return Toolbar
     * @throws Exception
     */
    public function getInstance($id = self::DEFAULT_INSTANCE_NAME)
    {
        if (!isset($this->instances[$id])) {
            $this->instances[$id] = new Toolbar($id);
            if ($id === static::DEFAULT_INSTANCE_NAME) {
                $this->preconfigureDefaultInstance($this->instances[$id]);
            }
        }
        return $this->instances[$id];
    }

    /**
     * @param Toolbar $menu
     * @throws Exception
     */
    private function preconfigureDefaultInstance(Toolbar $menu)
    {
        $menu->addItem((new ToolbarItem('Home', 'test_id'))
            ->setAction(new Route('dashboard'))
            ->addIcon(new Icon('fas fa-home text-white')));
    }

    /**
     * @return UrlGeneratorInterface
     */
    public function getRouter(): UrlGeneratorInterface
    {
        return $this->router;
    }

    /**
     * @param UrlGeneratorInterface $router
     * @return ToolbarManager
     */
    public function setRouter(UrlGeneratorInterface $router): ToolbarManager
    {
        $this->router = $router;
        return $this;
    }
}
