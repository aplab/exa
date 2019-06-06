<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 25.08.2018
 * Time: 17:56
 */

namespace App\Controller;


use App\Entity\FieldsExample;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FieldsExampleController
 * @package App\Controller
 * @Route("/admin/fields-example", name="admin_fields_example_")
 */
class FieldsExampleController extends ReferenceAdminController
{
    /**
     * @var string
     */
    protected $entityClassName = FieldsExample::class;
}
