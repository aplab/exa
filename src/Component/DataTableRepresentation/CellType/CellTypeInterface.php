<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 02.08.2018
 * Time: 10:48
 */

namespace Aplab\AplabAdminBundle\Component\DataTableRepresentation\CellType;


interface CellTypeInterface
{
    public function getType();

    public function getValue($entity);

    public function getUniqueId();

    public function getClass($entity);
}
