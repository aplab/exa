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
use SplFileInfo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FilesController
 * @package App\Controller
 */
class FilesController extends AbstractController
{
    /**
     * @var string
     */
    private $publicDir;

    /**
     * @var string
     */
    private $localStoragePath;

    public function __construct(string $public_dir, string $local_storage_path)
    {
        $this->publicDir = $public_dir;
        $this->localStoragePath = $local_storage_path;
    }

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
            /** @noinspection PhpParamsInspection */
            throw $this->createNotFoundException('File not found');
        }
        if ($file->getName() !== $name) {
            /** @noinspection PhpParamsInspection */
            throw $this->createNotFoundException('File not found');
        }
        $path_part = '/' . join('/', array_slice(str_split($file->getFilename(), 3), 0, 3));
        $mime = $file->getContentType();
        $path = new Path(
            $this->localStoragePath,
            $path_part,
            $file->getFilename()
        );
        $response = new BinaryFileResponse(new SplFileInfo($path));
        $response->setAutoEtag();
        $response->headers->set('Content-Type', $mime);
        return $response;
    }
}
