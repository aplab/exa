<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 02.08.2018
 * Time: 10:58
 */

namespace App\Component\InstanceEditor\FieldType;


interface FieldTypeInterface
{
    public function getType();
    public function getValue();
    public function getUniqueId();
}
