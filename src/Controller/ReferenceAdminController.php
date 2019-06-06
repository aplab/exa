<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 25.08.2018
 * Time: 17:56
 */

namespace App\Controller;


use App\Component\DataTableRepresentation\DataTableRepresentation;
use App\Component\InstanceEditor\InstatceEditorManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ReferenceAdminController
 * @package App\Controller
 */
abstract class ReferenceAdminController extends BaseAdminController
{
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
        $toolbar->addHandler('Clone selected', 'AplDataTable.getInstance().duplicate();', 'far fa-clone text-warning');

        $data_table = $data_table_representation->getDataTable($this->getEntityClassName());
        $pager = $data_table->getPager();
        return $this->render('@AplabAdmin/data-table/data-table.html.twig', get_defined_vars());
    }

    /**
     * @Route("/", name="list_param", methods="POST")
     * @param DataTableRepresentation $data_table_representation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \ReflectionException
     */
    public function setListParam(DataTableRepresentation $data_table_representation)
    {
        if (isset($_POST['itemsPerPage']) && isset($_POST['pageNumber'])) {
            $data_table = $data_table_representation->getDataTable($this->getEntityClassName());
            $pager = $data_table->getPager();
            $pager->setItemsPerPage($_POST['itemsPerPage']);
            $pager->setCurrentPage($_POST['pageNumber']);
        }
        return $this->redirectToRoute($this->getRouteAnnotation()->getName() . 'list');
    }

    /**
     * @Route("/del", name="drop", methods="POST")
     */
    public function dropItem()
    {
        $class = $this->getEntityClassName();
        $entity_manager = $this->getDoctrine()->getManager();
        $class_metadata = $entity_manager->getClassMetadata($class);
        $pk = $class_metadata->getIdentifier();
        /**
         * @TODO composite key support
         */
        if (empty($pk)) {
            throw new \RuntimeException('identifier not found');
        }
        if (sizeof($pk) > 1) {
            throw new \RuntimeException('composite identifier not supported');
        }
        $key = reset($pk);
        $ids = $_POST[$key];
        $ids = json_decode($ids);
        $items = $entity_manager->getRepository($class)->findBy([$key => $ids]);
        foreach ($items as $item) {
            $entity_manager->remove($item);
        }
        $entity_manager->flush();
        return $this->redirectToRoute($this->getRouteAnnotation()->getName() . 'list');
    }

    /**
     * @Route("/duplicate", name="duplicate", methods="POST")
     */
    public function duplicate()
    {
        $class = $this->getEntityClassName();
        $entity_manager = $this->getDoctrine()->getManager();
        $class_metadata = $entity_manager->getClassMetadata($class);
        $pk = $class_metadata->getIdentifier();
        /**
         * @TODO composite key support
         */
        if (empty($pk)) {
            throw new \RuntimeException('identifier not found');
        }
        if (sizeof($pk) > 1) {
            throw new \RuntimeException('composite identifier not supported');
        }
        $key = reset($pk);
        $ids = $_POST[$key];
        $ids = json_decode($ids);
        $items = $entity_manager->getRepository($class)->findBy([$key => $ids]);
        try {
            foreach ($items as $item) {
                $copy = clone($item);
                $entity_manager->persist($copy);
            }
            $entity_manager->flush();
        } catch (\Throwable $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute($this->getRouteAnnotation()->getName() . 'list');
        }
        return $this->redirectToRoute($this->getRouteAnnotation()->getName() . 'list');
    }

    /**
     * @Route("/add", name="add", methods={"GET"})
     * @param InstatceEditorManager $instatceEditorManager
     * @return Response
     * @throws \App\Component\Toolbar\Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \ReflectionException
     */
    public function addItem(InstatceEditorManager $instatceEditorManager)
    {
        $helper = $this->adminControllerHelper;
        $toolbar = $this->adminControllerHelper->getToolbar();
        $toolbar->addHandler('Save', 'AplInstanceEditor.getInstance().save();', 'fas fa-save text-success');
        $toolbar->addHandler('Save and exit', 'AplInstanceEditor.getInstance().saveAndExit();',
            'fas fa-save text-success');
        $toolbar->addUrl('Exit without saving', $helper->getModulePath(), 'fas fa-sign-out-alt text-danger flip-h');
        $entity_class_name = $this->getEntityClassName();
        $item = new $entity_class_name;
        $instance_editor = $instatceEditorManager->getInstanceEditor($item);
        return $this->render('@AplabAdmin/instance-editor/instance-editor.html.twig', get_defined_vars());
    }

    /**
     * @Route("/add", name="create", methods={"POST"})
     * @param InstatceEditorManager $instatceEditorManager
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \ReflectionException
     */
    public function createItem(InstatceEditorManager $instatceEditorManager, Request $request,
                               ValidatorInterface $validator)
    {
        $entity_class_name = $this->getEntityClassName();
        $item = new $entity_class_name;
        $instance_editor = $instatceEditorManager->getInstanceEditor($item);
        try {
            $instance_editor->handleRequest($request);
            $errors = $validator->validate($item);
        } catch (\Throwable $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute($this->getRouteAnnotation()->getName() . 'add');
        }
        if ($request->request->has('saveAndExit')) {
            return $this->redirectToRoute($this->getRouteAnnotation()->getName() . 'list');
        }
        if ($item->getId()) {
            return $this->redirectToRoute($this->getRouteAnnotation()->getName() . 'edit', ['id' => $item->getId()]);
        }
    }

    /**
     * @Route("/{id}", name="edit", methods={"GET"})
     * @param $id
     * @param InstatceEditorManager $instance_editor_manager
     * @return Response
     * @throws \App\Component\Toolbar\Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \ReflectionException
     */
    public function editItem($id, InstatceEditorManager $instance_editor_manager)
    {
        $helper = $this->adminControllerHelper;
        $toolbar = $this->adminControllerHelper->getToolbar();
        $toolbar->addHandler('Save', 'AplInstanceEditor.getInstance().save();', 'fas fa-save text-success');
        $toolbar->addHandler('Save and exit', 'AplInstanceEditor.getInstance().saveAndExit();',
            'fas fa-save text-success');
        $toolbar->addUrl('Exit without saving', $helper->getModulePath(), 'fas fa-sign-out-alt text-danger flip-h');
        $entity_class_name = $this->getEntityClassName();
        $item = $instance_editor_manager->getEntityManagerInterface()->find($entity_class_name, $id);
        if (!$item) {
            return $this->redirectToRoute($this->getRouteAnnotation()->getName() . 'list');
        }
        $instance_editor = $instance_editor_manager->getInstanceEditor($item);
        return $this->render('@AplabAdmin/instance-editor/instance-editor.html.twig', get_defined_vars());
    }

    /**
     * @Route("/{id}", name="update", methods={"POST"})
     * @param $id
     * @param InstatceEditorManager $instance_editor_manager
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \ReflectionException
     */
    public function updateItem($id, InstatceEditorManager $instance_editor_manager, Request $request)
    {
        $entity_class_name = $this->getEntityClassName();
        $item = $instance_editor_manager->getEntityManagerInterface()->find($entity_class_name, $id);
        if (!$item) {
            return $this->redirectToRoute($this->getRouteAnnotation()->getName() . 'list');
        }
        $instance_editor = $instance_editor_manager->getInstanceEditor($item);
        try {
            $instance_editor->handleRequest($request);
            //$errors = $validator->validate($item);
        } catch (\Throwable $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute($this->getRouteAnnotation()->getName() . 'edit', ['id' => $id]);
        }
        if ($request->request->has('saveAndExit')) {
            return $this->redirectToRoute($this->getRouteAnnotation()->getName() . 'list');
        }
        return $this->redirectToRoute($this->getRouteAnnotation()->getName() . 'edit', ['id' => $id]);
    }
}
