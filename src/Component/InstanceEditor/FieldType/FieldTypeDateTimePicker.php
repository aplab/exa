<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 02.08.2018
 * Time: 10:59
 */

namespace Aplab\AplabAdminBundle\Component\InstanceEditor\FieldType;


class FieldTypeDateTimePicker extends FieldTypeAbstract
{
    public function getValue()
    {
        /**
         * @var \DateTime $value
         */
        $value = parent::getValue();
        if (!$value) {
            return null;
        }
        $value = $value->format('Y-m-d H:i:s');
        return $value;
    }
}