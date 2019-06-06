<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 20.08.2018
 * Time: 23:47
 */

namespace Aplab\AplabAdminBundle\Component\DataTableRepresentation\CellType;

use Aplab\AplabAdminBundle\Component\DataTableRepresentation\DataTableCell;

/**
 * Class CellTypeFactory
 * @package Aplab\AplabAdminBundle\Component\DataTableRepresentation\CellType
 */
class CellTypeFactory
{
    const PREFIX = '\\CellType';

    public function create(DataTableCell $cell, $type)
    {
        $class_name = __NAMESPACE__.static::PREFIX.$type;
        return new $class_name($cell);
    }
}