<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 02.08.2018
 * Time: 10:58
 */

namespace Aplab\AplabAdminBundle\Component\InstanceEditor\FieldType;


use Aplab\AplabAdminBundle\Component\InstanceEditor\InstanceEditorField;

abstract class FieldTypeAbstract implements FieldTypeInterface
{
    /**
     * FieldTypeAbstract constructor.
     * @param InstanceEditorField $field
     */
    public function __construct(InstanceEditorField $field)
    {
        $tmp = explode(FieldTypeFactory::PREFIX, static::class);
        $this->type = strtolower(end($tmp));
        $this->field = $field;
    }

    /**
     * @var InstanceEditorField
     */
    protected $field;

    /**
     * @var string
     */
    protected $type;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        $entity = $this->field->getEntity();
        $property_name = $this->field->getPropertyName();
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
     * @return string
     */
    public function getUniqueId()
    {
        return spl_object_id($this);
    }
}