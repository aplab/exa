<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 09.09.2018
 * Time: 11:53
 */

namespace Aplab\AplabAdminBundle\Component\DataTableRepresentation;


class DataTableTreeMod extends DataTable
{
    /**
     * @param void
     * @return int
     */
    public function getCount():int
    {
        if (is_null($this->count)) {
            $this->count = $this->entityManager->getRepository($this->entityClassName)->getRootsCount([]);
        }
        return $this->count;
    }

    /**
     * Temporary stub
     * @return object[]
     */
    public function getItems()
    {
        $pager = $this->getPager();
        return $this->entityManager->getRepository($this->entityClassName)->rootPage(
            $pager->getItemsPerPage(),
            $pager->getOffset()
        );
    }
}