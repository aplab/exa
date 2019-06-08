<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 25.08.2018
 * Time: 17:56
 */

namespace App\Controller;


use App\Component\DataTableRepresentation\DataTableRepresentation;
use App\Entity\UserFiles\File;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserFilesController
 * @package App\Controller
 * @Route("/admin/user-files", name="admin_user_files_")
 */
class UserFilesController extends ReferenceAdminController
{
    /**
     * @var string
     */
    protected $entityClassName = File::class;

    /**
     * @Route("/", name="list", methods="GET")
     * @param DataTableRepresentation $data_table_representation
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \ReflectionException
     * @throws \App\Component\Toolbar\Exception
     */
    public function listItems(DataTableRepresentation $data_table_representation)
    {
        $helper = $this->adminControllerHelper;
        $toolbar = $this->adminControllerHelper->getToolbar();
        $toolbar->addUrl('New item', $helper->getModulePath('add'), 'fas fa-plus text-success');
        $toolbar->addHandler('Delete selected', 'AplDataTable.getInstance().del();', 'fas fa-times text-danger');
        $toolbar->addHandler('Batch', 'AplDataTable.getInstance().batchAddFilesPlugin()', 'fas fa-th');

        $data_table = $data_table_representation->getDataTable($this->getEntityClassName());
        $pager = $data_table->getPager();
        return $this->render('data-table/data-table.html.twig', get_defined_vars());
    }
}
