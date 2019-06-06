<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 02.08.2018
 * Time: 10:49
 */

namespace App\Component\DataTableRepresentation\CellType;


use App\Component\DataTableRepresentation\DataTableCell;

abstract class CellTypeAbstract implements CellTypeInterface
{
    /**
     * CellTypeAbstract constructor.
     * @param DataTableCell $cell
     */
    public function __construct(DataTableCell $cell)
    {
        $tmp = explode(CellTypeFactory::PREFIX, static::class);
        $this->type = strtolower(end($tmp));
        $this->cell = $cell;
    }

    /**
     * @var string
     */
    protected $type;

    /**
     * @var DataTableCell
     */
    protected $cell;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getUniqueId()
    {
        return spl_object_id($this);
    }

    /**
     * @param object $entity
     * @return mixed
     */
    public function getValue($entity)
    {
        $property_name = $this->cell->getPropertyName();
        $property_name_ucfirst = ucfirst($property_name);
        $accessors = [
            'getter' => 'get' . $property_name_ucfirst,
            'isser' => 'is' . $property_name_ucfirst,
            'hasser' => 'has' . $property_name_ucfirst
        ];
        foreach ($accessors as $accessor) {
            if (method_exists($entity, $accessor)) {
                return $entity->$accessor();
            }
        }
        throw new \LogicException('Unable to access property ' . get_class($entity) . '::' . $property_name);
    }

    /**
     * @param $entity
     * @return string
     */
    public function getClass($entity)
    {
        return get_class($entity);
    }
}
