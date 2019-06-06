<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 05.09.2018
 * Time: 23:12
 */

namespace App\Component\DataTableRepresentation\CellType;


use App\Util\Path;

class CellTypeUserFileLink extends CellTypeAbstract
{
    /**
     * @var string
     */
    public static $prefix = '/files/';

    /**
     * @param object $entity
     * @return mixed
     */
    public function getValue($entity)
    {
        $value = parent::getValue($entity);
        if ($value) {
            $path = new Path(
                static::$prefix,
                $entity->getId(),
                $entity->getName()
            );
            return (string)$path;
        }
        return null;
    }
}
