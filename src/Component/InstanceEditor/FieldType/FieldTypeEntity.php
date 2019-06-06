<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 02.08.2018
 * Time: 10:59
 */

namespace App\Component\InstanceEditor\FieldType;


use App\Component\InstanceEditor\InstanceEditorField;

class FieldTypeEntity extends FieldTypeAbstract
{
    /**
     * @var string
     */
    protected $entityClass;

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

    public function getOptionsDataList()
    {
        $em = $this->field->getInstanceEditor()->getEntityManagerInterface();
        $repository = $em->getRepository($this->getEntityClass());
        $value = $this->getValue();
        return $repository->getOptionsDataList($value);
    }

    /**
     * @return InstanceEditorField
     */
    public function getField(): InstanceEditorField
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        if (is_null($this->entityClass)) {
            $this->entityClass = $this->getField()->getOptions()->data_class ?? get_class($this->field->getEntity());
        }
        return $this->entityClass;
    }
}
