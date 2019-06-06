<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 14.08.2018
 * Time: 13:51
 */

namespace Aplab\AplabAdminBundle\Component\Toolbar;


class ToolbarItem implements \JsonSerializable
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
     * @throws Exception
     */
    public function setId(string $id)
    {
        $this->id = $id;
        static::registerInstance($this);
    }

    /**
     * @var string
     */
    protected $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ToolbarItem
     */
    public function setName(string $name): ToolbarItem
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @var bool
     */
    protected $disabled = false;

    /**
     * Additional CSS class
     * @var string
     */
    protected $class;

    /**
     * The target attribute specifies where to open the linked document.
     * Variants: _blank|_self|_parent|_top|framename
     * @var string
     */
    protected $target;

    /**
     * @var Action
     */
    protected $action;

    /**
     * @var Icon[]
     */
    protected $icon = [];

    /**
     * ToolbarItem constructor.
     * @param string $name
     * @param string|null $id
     * @throws Exception
     */
    public function __construct(string $name, ?string $id = null)
    {
        if (is_null($id)) {
            $id = spl_object_hash($this);
        }
        $this->id = $id;
        $this->name = $name;
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
     * @return static|null
     */
    public static function getInstance(string $id)
    {
        return static::$instances[$id] ?? null;
    }

    /**
     * @param self $instance
     * @throws Exception
     */
    private static function registerInstance(ToolbarItem $instance)
    {
        $id = $instance->getId();
        if (array_key_exists($id, static::$instances)) {
            throw new Exception('Duplicate id: ' . $id);
        }
        static::$instances[$id] = $instance;
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * @param bool $disabled
     * @return ToolbarItem
     */
    public function setDisabled(bool $disabled): ToolbarItem
    {
        $this->disabled = (bool)$disabled;
        return $this;
    }

    /**
     * @return string
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

    /**
     * @param string $class
     * @return ToolbarItem
     */
    public function setClass(?string $class): ToolbarItem
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @return string
     */
    public function getTarget(): ?string
    {
        return $this->target;
    }

    /**
     * @param string $target
     * @return ToolbarItem
     */
    public function setTarget(?string $target): ToolbarItem
    {
        $this->target = $target;
        return $this;
    }

    /**
     * @return Action
     */
    public function getAction(): ?Action
    {
        return $this->action;
    }

    /**
     * @param Action $action
     * @return ToolbarItem
     */
    public function setAction(Action $action): ToolbarItem
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return Icon[]
     */
    public function getIcon(): array
    {
        return $this->icon;
    }

    /**
     * @param Icon $icon
     * @return ToolbarItem
     */
    public function addIcon(Icon $icon): ToolbarItem
    {
        $this->icon[] = $icon;
        return $this;
    }

    /**
     * @return $this
     */
    public function clearIcon()
    {
        $this->icon = [];
        return $this;
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
        $data = [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'disabled' => $this->isDisabled(),
            'target' => $this->getTarget(),
            'class' => $this->getClass()
        ];
        $action = ($this->getAction());
        if ($action instanceof Url) {
            $data['url'] = $action->getUrl();
        }
        if ($action instanceof Route) {
            $data['url'] = $action->generateUrl();
        }
        if ($action instanceof Handler) {
            $data['handler'] = $action->getHandler();
        }
        $data['icon'] = array_map(function (Icon $i) {
            return $i->getIcon();
        }, $this->getIcon());
        return $data;
    }
}