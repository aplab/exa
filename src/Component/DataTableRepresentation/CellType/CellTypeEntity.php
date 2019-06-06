<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 02.08.2018
 * Time: 10:52
 */

namespace Aplab\AplabAdminBundle\Component\DataTableRepresentation\CellType;


class CellTypeEntity extends CellTypeAbstract
{
    /**
     * @param object $entity
     * @return mixed
     */
    public function getValue($entity)
    {
        $value = parent::getValue($entity);
        if ($value) {
            $options = $this->cell->getOptions();
            $accessor = $options->accessor ?? 'getName';
            return $value->$accessor();
        }
        return null;
    }
}