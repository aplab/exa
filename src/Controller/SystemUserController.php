<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 25.08.2018
 * Time: 17:56
 */

namespace Aplab\AplabAdminBundle\Controller;


use Aplab\AplabAdminBundle\Entity\SystemUser;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NamedTimestampableController
 * @package Aplab\AplabAdminBundle\Controller
 * @Route("/admin/system-user", name="admin_system_user_")
 */
class SystemUserController extends ReferenceAdminController
{
    /**
     * @var string
     */
    protected $entityClassName = SystemUser::class;

    /**
     * @Route("/del", name="drop", methods="POST")
     */
    public function dropItem()
    {
        $user = $this->getUser();
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
            if ($item === $user) {
                $this->addFlash('warning', 'You can not delete the current user of the system.');
            } else {
                $entity_manager->remove($item);
            }
        }
        $entity_manager->flush();
        return $this->redirectToRoute($this->getRouteAnnotation()->getName() . 'list');
    }
}