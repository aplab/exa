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
 * @Route("/fields-example", name="fields_example_")
 */
class FieldsExampleController extends ReferenceController
{
    /**
     * @var string
     */
    protected $entityClassName = FieldsExample::class;
}
