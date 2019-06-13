<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 06.08.2018
 * Time: 10:31
 */

namespace App\Controller;

use App\Util\HtmlTitle;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @package App\Controller
 * @Route("", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @var HtmlTitle
     */
    private $htmlTitle;

    /**
     * AdminController constructor.
     * @param HtmlTitle $html_title
     */
    public function __construct(HtmlTitle $html_title)
    {
        $this->htmlTitle = $html_title;
    }

    /**
     * @Route("/", name="desktop")
     * @return Response
     */
    public function desktop() {
        $this->htmlTitle->setTitle('Dashboard');
        //$this->addFlash('notice', 'test message');
        return $this->render('admin.html.twig', get_defined_vars());
    }
}
