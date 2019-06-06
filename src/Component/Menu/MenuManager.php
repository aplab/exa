<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 13.08.2018
 * Time: 16:08
 */

namespace Aplab\AplabAdminBundle\Component\Menu;


use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class MenuManager
 * @package Aplab\AplabAdminBundle\Component\Menu
 */
class MenuManager
{
    const DEFAULT_ID = 'MainMenu';

    const STRUCTURE_LOCATION_DEFAULT = __DIR__ . '/menu_structure_default.json';

    const ID_SEPARATOR = '-';

    const ICON_SEPARATOR = ',';

    const KEY_ICON = 'icon';

    const KEY_ITEMS = 'items';

    const KEY_ACTION = 'action';

    const KEY_HANDLER = 'handler';

    const KEY_URL = 'url';

    const KEY_ROUTE = 'route';

    const KEY_ID = 'id';

    const KEY_NAME = 'name';

    const KEY_DISABLED = 'disabled';

    const KEY_CLASS = 'class';

    const KEY_TARGET = 'target';

    const KEY_PARAMETERS = 'parameters';

    private $cacheKey;

    private $cache;

    private $router;

    private $structureLocation;

    private $structure;

    /**
     * MenuManager constructor.
     * @param null|string $structure_location
     * @param CacheInterface $cache
     * @param UrlGeneratorInterface $router
     */
    public function __construct(?string $structure_location, CacheInterface $cache, UrlGeneratorInterface $router)
    {
        $this->cache = $cache;
        $this->router = $router;
        $this->structureLocation = $structure_location ?? static::STRUCTURE_LOCATION_DEFAULT;
        $this->cacheKey = join('.', [
            md5(__CLASS__),
            md5(static::STRUCTURE_LOCATION_DEFAULT),
            md5($this->structureLocation)
        ]);
        Route::setRouter($this->getRouter());
    }

    /**
     * @return string
     */
    public function getCacheKey(): string
    {
        return $this->cacheKey;
    }

    /**
     * @return CacheInterface
     */
    public function getCache(): CacheInterface
    {
        return $this->cache;
    }

    /**
     * @return null|string
     */
    public function getStructureLocation(): ?string
    {
        return $this->structureLocation;
    }

    /**
     * @return mixed
     * @throws Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getStructure()
    {
        if (is_null($this->structure)) {
            $this->structure = $this->cache->get($this->cacheKey);
            if (is_null($this->structure)) {
                $this->buildStructure();
                $this->cache->set($this->cacheKey, $this->structure);
            }
        }
        return $this->structure;
    }

    /**
     * @param $id
     * @return Menu
     * @throws Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getMenu($id = self::DEFAULT_ID)
    {
        $structure = $this->getStructure();
        return $structure[$id] ?? null;
    }

    /**
     * @throws Exception
     */
    private function buildStructure(): void
    {
        $json = file_get_contents($this->structureLocation);
        $data = json_decode($json, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \RuntimeException(json_last_error_msg(), json_last_error());
        }
        foreach ($data as $id => $menu_data) {
            $menu = new Menu($id);
            $this->structure[$id] = $menu;
            $this->processItem($menu, $menu_data);
        }
    }

    /**
     * @param Menu|MenuItem $item
     * @param array|null $item_data
     * @throws Exception
     */
    private function processItem($item, ?array $item_data): void
    {
        $container_id = $item->getId();
        $children_data = $item_data[static::KEY_ITEMS] ?? [];
        if (is_array($children_data)) {
            foreach ($children_data as $child_id => $child_data) {
                $child_full_id = join(
                    static::ID_SEPARATOR,
                    [
                        $container_id,
                        $child_id
                    ]
                );
                $child = new MenuItem(
                    $child_data[static::KEY_ID] ?? $child_full_id,
                    $child_data[static::KEY_NAME] ?? 'untitled'
                );
                $this->initIcon($child, $child_data);
                $this->initAction($child, $child_data);
                $child->setDisabled($child_data[static::KEY_DISABLED] ?? false);
                $child->setClass($child_data[static::KEY_CLASS] ?? null);
                $child->setTarget($child_data[static::KEY_TARGET] ?? null);
                $item->addItem($child);
                // Recursive call
                $this->processItem($child, $child_data);
            }
        }
    }

    /**
     * @param MenuItem $item
     * @param array $item_data
     */
    private function initIcon(MenuItem $item, array $item_data): void
    {
        if (!isset($item_data[static::KEY_ICON])) {
            return;
        }
        $icons = explode(static::ICON_SEPARATOR, $item_data[static::KEY_ICON]);
        foreach ($icons as $icon) {
            $icon = trim($icon);
            if ($icon) {
                $item->addIcon(new Icon(trim($icon)));
            }
        }
    }

    /**
     * @param MenuItem $item
     * @param array $item_data
     */
    private function initAction(MenuItem $item, array $item_data): void
    {
        $action = [];
        if (isset($item_data[static::KEY_URL])) {
            $action[static::KEY_URL] = new Url($item_data[static::KEY_URL]);
        }
        if (isset($item_data[static::KEY_HANDLER])) {
            $action[static::KEY_HANDLER] = new Handler($item_data[static::KEY_HANDLER]);
        }
        if (isset($item_data[static::KEY_ROUTE])) {
            $action[static::KEY_ROUTE] = new Route(
                $item_data[static::KEY_ROUTE],
                $item_data[static::KEY_PARAMETERS] ?? []
            );
        }
        if (empty($action)) {
            return;
        }
        if (sizeof($action) > 1) {
            throw new \RuntimeException('only one action can be defined');
        }
        $item->setAction(reset($action));
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
     * @return MenuManager
     */
    public function setRouter(UrlGeneratorInterface $router): MenuManager
    {
        $this->router = $router;
        return $this;
    }
}