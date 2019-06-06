<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 20.08.2018
 * Time: 23:47
 */

namespace App\Component\InstanceEditor\FieldType;

use App\Component\InstanceEditor\InstanceEditorField;

/**
 * Class FieldTypeFactory
 * @package App\Component\DataTableRepresentation\CellType
 */
class FieldTypeFactory
{
    const PREFIX = '\\FieldType';

    public function create(InstanceEditorField $field, $type)
    {
        $class_name = __NAMESPACE__ . static::PREFIX . $type;
        return new $class_name($field);
    }
}
