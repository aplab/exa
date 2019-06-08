<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 02.08.2018
 * Time: 10:59
 */

namespace App\Component\InstanceEditor\FieldType;


use DateTime;

class FieldTypeDateTime extends FieldTypeAbstract
{
    public function getValue()
    {
        /**
         * @var DateTime $value
         */
        $value = parent::getValue();
        if (!$value) {
            return null;
        }
        $value = $value->format('Y-m-d H:i:s');
        return $value;
    }
}
