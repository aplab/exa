<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 06.09.2018
 * Time: 0:20
 */

namespace App\Controller;


use App\Entity\UserFiles\File;
use App\Util\Path;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FilesController
 * @package App\Controller
 */
class FilesController extends Controller
{
    /**
     * @Route("/files/{id}/{name}",
     *     name="files", methods="GET",
     *     requirements={
     *         "offset"="^\d+$"
     *     }
     * )
     * @param $id
     * @param $name
     * @return BinaryFileResponse
     */
    public function sendFile($id, $name) {
        $file = $this->getDoctrine()
            ->getRepository(File::class)
            ->find($id);
        if (!($file instanceof File)) {
            throw $this->createNotFoundException(
                'File not found'
            );
        }
        if ($file->getName() !== $name) {
            throw $this->createNotFoundException(
                'File not found'
            );
        }
        $path_part = '/' . join('/', array_slice(str_split($file->getFilename(), 3), 0, 3));
        $dir = dirname($this->get('kernel')->getRootDir(), 1);
        $mime = $file->getContentType();
        $path = new Path(
            $dir,
            '/public/aplab/filestorage/',
            $path_part,
            $file->getFilename()
        );
        $response = new BinaryFileResponse(new \SplFileInfo($path));
        $response->setAutoEtag();
        $response->headers->set('Content-Type', $mime);
        return $response;
    }
}
