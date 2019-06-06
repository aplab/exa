<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 02.08.2018
 * Time: 10:52
 */

namespace Aplab\AplabAdminBundle\Component\DataTableRepresentation\CellType;


class CellTypeTree extends CellTypeAbstract
{
    /**
     * @param object $entity
     * @return mixed
     */
    public function getValue($entity)
    {
        $value = parent::getValue($entity);
        if ($value) {
            return $value;
        }
        return null;
    }

    public function getPrefix($entity)
    {
        $level = $entity->level ?? 0;
        return str_repeat(html_entity_decode('&bull;&nbsp;'), $level);
    }
}