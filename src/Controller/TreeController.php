<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 25.08.2018
 * Time: 17:56
 */

namespace Aplab\AplabAdminBundle\Controller;


use Aplab\AplabAdminBundle\Component\DataTableRepresentation\DataTableRepresentation;
use Aplab\AplabAdminBundle\Component\DataTableRepresentation\DataTableRepresentationTreeMod;
use Aplab\AplabAdminBundle\Component\DataTableRepresentation\DataTableTreeMod;
use Aplab\AplabAdminBundle\Entity\AdjacencyList\ListItem;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NamedTimestampableController
 * @package Aplab\AplabAdminBundle\Controller
 * @Route("/admin/tree", name="admin_tree_")
 */
class TreeController extends ReferenceAdminController
{
    /**
     * @var string
     */
    protected $entityClassName = ListItem::class;

    /**
     * @Route("/", name="list", methods="GET")
     * @param DataTableRepresentation $data_table_representation
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Aplab\AplabAdminBundle\Component\Toolbar\Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \ReflectionException
     */
    public function listItems(DataTableRepresentation $data_table_representation)
    {
        $helper = $this->adminControllerHelper;
        $toolbar = $this->adminControllerHelper->getToolbar();
        $toolbar->addUrl('New item', $helper->getModulePath('add'), 'fas fa-plus text-success');
        $toolbar->addHandler('Delete selected', 'AplDataTable.getInstance().del();', 'fas fa-times text-danger');
        $toolbar->addHandler('Clone selected', 'AplDataTable.getInstance().duplicate();', 'far fa-clone text-warning');

        $data_table = $data_table_representation->getDataTable($this->getEntityClassName(), DataTableTreeMod::class);
        $pager = $data_table->getPager();
        return $this->render('@AplabAdmin/data-table/data-table.html.twig', get_defined_vars());
    }
}