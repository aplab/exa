<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 22.08.2018
 * Time: 15:20
 */

namespace Aplab\AplabAdminBundle\Component\ActionMenu;

/**
 * Class ActionMenu
 * @package Aplab\AplabAdminBundle\Component\ActionMenu
 */
class ActionMenu implements \JsonSerializable
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
     * @var MenuItem[]
     */
    protected $items = [];

    /**
     * @return MenuItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param MenuItem $item
     * @return ActionMenu
     */
    public function addItem(MenuItem $item)
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
     * @return ActionMenu|null
     */
    public static function getInstance(string $id)
    {
        return static::$instances[$id] ?? null;
    }

    /**
     * @param self $instance
     * @throws Exception
     */
    private static function registerInstance(ActionMenu $instance)
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
            'items' => array_map(function(MenuItem $i) {
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
}