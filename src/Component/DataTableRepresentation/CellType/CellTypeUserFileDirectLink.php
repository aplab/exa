<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 05.09.2018
 * Time: 23:12
 */

namespace App\Component\DataTableRepresentation\CellType;


use App\Util\Path;

class CellTypeUserFileDirectLink extends CellTypeAbstract
{
    /**
     * @var string
     */
    public static $prefix = '/aplab/filestorage/';

    /**
     * @param object $entity
     * @return mixed
     */
    public function getValue($entity)
    {
        $value = parent::getValue($entity);
        if ($value) {
            $path_part = '/' . join('/', array_slice(str_split($value, 3), 0, 3));
            $path = new Path(
                static::$prefix,
                $path_part,
                $value
            );
            return (string)$path;
        }
        return null;
    }

}
