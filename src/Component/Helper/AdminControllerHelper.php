<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 26.08.2018
 * Time: 12:03
 */

namespace App\Component\Helper;


use App\Component\ActionMenu\ActionMenu;
use App\Component\ActionMenu\ActionMenuManager;
use App\Component\ActionMenu\Exception;
use App\Component\Menu\Menu;
use App\Component\Menu\MenuManager;
use App\Component\Toolbar\Toolbar;
use App\Component\Toolbar\ToolbarManager;
use Doctrine\Common\Annotations\Reader;
use Psr\SimpleCache\InvalidArgumentException;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class AdminControllerHelper
{
    /**
     * @var MenuManager
     */
    protected $menuManager;

    /**
     * @var ActionMenuManager
     */
    protected $actionMenuManager;

    /**
     * @var ToolbarManager
     */
    protected $toolbarManager;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var Reader
     */
    protected $annotationsReader;

    /**
     * AdminControllerHelper constructor.
     * @param MenuManager $menuManager
     * @param ActionMenuManager $actionMenuManager
     * @param ToolbarManager $toolbarManager
     * @param RequestStack $requestStack
     * @param Reader $annotations_reader
     */
    public function __construct(MenuManager $menuManager,
                                ActionMenuManager $actionMenuManager,
                                ToolbarManager $toolbarManager,
                                RequestStack $requestStack,
                                Reader $annotations_reader)
    {
        $this->annotationsReader = $annotations_reader;
        $this->menuManager = $menuManager;
        $this->actionMenuManager = $actionMenuManager;
        $this->toolbarManager = $toolbarManager;
        $this->requestStack = $requestStack;
    }

    /**
     * @return MenuManager
     */
    public function getMenuManager(): MenuManager
    {
        return $this->menuManager;
    }

    /**
     * @return ActionMenuManager
     */
    public function getActionMenuManager(): ActionMenuManager
    {
        return $this->actionMenuManager;
    }

    /**
     * @return ToolbarManager
     */
    public function getToolbarManager(): ToolbarManager
    {
        return $this->toolbarManager;
    }

    /**
     * @param null $id
     * @return Menu
     * @throws \App\Component\Menu\Exception
     * @throws InvalidArgumentException
     */
    public function getMenu($id = null)
    {
        if (is_null($id)) {
            return $this->menuManager->getMenu();
        }
        return $this->menuManager->getMenu($id);
    }

    /**
     * @param null $id
     * @return Toolbar
     * @throws \App\Component\Toolbar\Exception
     */
    public function getToolbar($id = null)
    {
        if (is_null($id)) {
            return $this->toolbarManager->getInstance();
        }
        return $this->toolbarManager->getInstance($id);
    }

    /**
     * @param null $id
     * @return ActionMenu
     * @throws Exception
     */
    public function getActionMenu($id = null)
    {
        if (is_null($id)) {
            return $this->actionMenuManager->getInstance();
        }
        return $this->actionMenuManager->getInstance($id);
    }

    /**
     * @return RequestStack
     */
    public function getRequestStack(): RequestStack
    {
        return $this->requestStack;
    }

    /**
     * @return null|Request
     */
    public function getMasterRequest()
    {
        return $this->requestStack->getMasterRequest();
    }

    /**
     * @param null|string $suffix
     * @return string
     */
    public function getModulePath(?string $suffix = null): string
    {
        if (is_scalar($suffix)) {
            $suffix = ltrim($suffix, '/');
        }
        $data = explode('/', '/' . trim($this->getMasterRequest()->getPathInfo(), '/'));
        if (sizeof($data) < 2) {
            throw new RuntimeException('unable to get module path not from a module');
        }
        $data = array_slice($data, 0, 3);
        if ($suffix) {
            $data[] = $suffix;
        }
        return join('/', $data);
    }

    /**
     * @return string
     */
    public function getBundlePath()
    {
        $data = explode('/', '/' . trim($this->getMasterRequest()->getPathInfo(), '/'));
        if (sizeof($data) < 2) {
            throw new RuntimeException('unable to get bundle path not from a bundle');
        }
        return join('/', array_slice($data, 0, 3));
    }

    /**
     * @return Reader
     */
    public function getAnnotationsReader(): Reader
    {
        return $this->annotationsReader;
    }
}
