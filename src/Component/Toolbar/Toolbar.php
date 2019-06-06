<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 22.08.2018
 * Time: 15:20
 */

namespace App\Component\Toolbar;

/**
 * Class Toolbar
 * @package App\Component\Toolbar
 */
class Toolbar implements \JsonSerializable
{
    /**
     * @var static[]
     */
    protected static $instances = [];

    /**
     * @var string
     */
    protected $id;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return static
     * @throws Exception
     */
    public function setId(string $id)
    {
        $this->id = $id;
        static::registerInstance($this);
        return $this;
    }

    /**
     * @var ToolbarItem[]
     */
    protected $items = [];

    /**
     * @return ToolbarItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param ToolbarItem $item
     * @return Toolbar
     */
    public function addItem(ToolbarItem $item)
    {
        $this->items[] = $item;
        return $this;
    }

    /**
     * MainMenu constructor.
     * @param string $id
     * @throws Exception
     */
    public function __construct(string $id)
    {
        $this->id = $id;
        static::registerInstance($this);
    }

    /**
     * @throws Exception
     */
    public function __wakeup()
    {
        static::registerInstance($this);
    }

    /**
     * @param string $id
     * @return Toolbar|null
     */
    public static function getInstance(string $id)
    {
        return static::$instances[$id] ?? null;
    }

    /**
     * @param self $instance
     * @throws Exception
     */
    private static function registerInstance(Toolbar $instance)
    {
        $id = $instance->getId();
        if (array_key_exists($id, static::$instances)) {
            throw new Exception('Duplicate id: ' . $id);
        }
        static::$instances[$id] = $instance;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'items' => array_map(function(ToolbarItem $i) {
                return $i->jsonSerialize();
            }, $this->items)
        ];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this);
    }

    /**
     * @param int $options
     * @return string
     */
    public function __toJson($options = JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE)
    {
        return json_encode($this, $options);
    }

    /**
     * Add item as route from scratch
     * Icon must be a string, comma-separated string, array, array of comma-separated strings or mixed
     * @param string $item_title
     * @param string $route_name
     * @param string|array $icon
     * @param array $route_parameters
     * @param null|string $item_id
     * @return ToolbarItem|mixed
     * @throws Exception
     */
    public function addRoute(string $item_title, string $route_name, $icon = [],
                             $route_parameters = [], ?string $item_id = null)
    {
        $item = new ToolbarItem($item_title, $item_id);
        $route = new Route($route_name, $route_parameters);
        if (!is_array($icon)) {
            $icon = [$icon];
        }
        foreach ($icon as $icons_data) {
            $icon_data = explode(',', $icons_data);
            foreach ($icon_data as $icon_data_item)
            $item->addIcon(new Icon(trim($icon_data_item)));
        }
        $item->setAction($route);
        $this->addItem($item);
        return $item;
    }

    /**
     * Add item as handler from scratch
     * Icon must be a string, comma-separated string, array, array of comma-separated strings or mixed
     * @param string $item_title
     * @param string $handler_code
     * @param string|array $icon
     * @param null|string $item_id
     * @return ToolbarItem|mixed
     * @throws Exception
     */
    public function addHandler(string $item_title, string $handler_code, $icon = [], ?string $item_id = null)
    {
        $item = new ToolbarItem($item_title, $item_id);
        $handler = new Handler($handler_code);
        if (!is_array($icon)) {
            $icon = [$icon];
        }
        foreach ($icon as $icons_data) {
            $icon_data = explode(',', $icons_data);
            foreach ($icon_data as $icon_data_item)
            $item->addIcon(new Icon(trim($icon_data_item)));
        }
        $item->setAction($handler);
        $this->addItem($item);
        return $item;
    }

    /**
     * Add item as url from scratch
     * Icon must be a string, comma-separated string, array, array of comma-separated strings or mixed
     * @param string $item_title
     * @param string $url
     * @param string|array $icon
     * @param null|string $item_id
     * @return ToolbarItem|mixed
     * @throws Exception
     */
    public function addUrl(string $item_title, string $url, $icon = [], ?string $item_id = null)
    {
        $item = new ToolbarItem($item_title, $item_id);
        $handler = new Url($url);
        if (!is_array($icon)) {
            $icon = [$icon];
        }
        foreach ($icon as $icons_data) {
            $icon_data = explode(',', $icons_data);
            foreach ($icon_data as $icon_data_item)
            $item->addIcon(new Icon(trim($icon_data_item)));
        }
        $item->setAction($handler);
        $this->addItem($item);
        return $item;
    }
}
