<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 04.09.2018
 * Time: 14:25
 */

namespace App\Controller;


use App\Component\FileStorage\LocalStorage;
use App\Component\Uploader\FileUploader;
use App\Component\Uploader\ImageUploader;
use App\Entity\HistoryUploadImage;
use Exception;
use Respect\Validation\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * Class FileController
 * @package App\Controller
 * @Route("/xhr", name="xhr_")
 */
class XhrController extends AbstractController
{
    /**
     * @Route("/uploadImage/", name="upload_image", methods="POST")
     * @param LocalStorage $localStorage
     * @return JsonResponse
     */
    public function uploadImage(LocalStorage $localStorage)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $uploader = new ImageUploader($localStorage, $entity_manager);
        try {
            $url = $uploader->receive();
            return new JsonResponse([
                'status' => 'ok',
                'url' => $url
            ]);
        } catch (Throwable $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
                'code' => $exception->getCode()
            ]);
        }
    }

    /**
     * @Route("/uploadFile/", name="upload_file", methods="POST")
     * @param LocalStorage $localStorage
     * @return JsonResponse
     */
    public function uploadFile(LocalStorage $localStorage)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $uploader = new FileUploader($localStorage, $entity_manager);
        try {
            $url = $uploader->receive();
            return new JsonResponse([
                'status' => 'ok',
                'url' => $url
            ]);
        } catch (Throwable $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
                'code' => $exception->getCode()
            ]);
        }
    }

    /**
     * @Route("/historyUploadImage/listItems/{offset}/",
     *     name="history_upload_image_list_items", methods="GET",
     *     requirements={"offset"="^\d+$"})
     * @param int $offset
     * @return JsonResponse
     */
    public function historyUploadImageListItems($offset)
    {
        $items = $this->getDoctrine()->getRepository(HistoryUploadImage::class)->findBy(
            [],
            ['favorites' => 'DESC', 'id' => 'desc'],
            103, $offset
        );
        return new JsonResponse($items);
    }

    /**
     * @Route("/historyUploadImage/listFavorites/{offset}/",
     *     name="history_upload_image_list_favorites", methods="GET",
     *     requirements={"offset"="^\d+$"})
     * @param int $offset
     * @return JsonResponse
     */
    public function historyUploadImageListFavorites($offset)
    {
        $items = $this->getDoctrine()->getRepository(HistoryUploadImage::class)->findBy(
            ['favorites' => 1],
            ['favorites' => 'DESC', 'id' => 'desc'],
            103, $offset
        );
        return new JsonResponse($items);
    }

    /**
     * @Route("/historyUploadImage/favItem/{id}/",
     *     name="history_upload_image_fav_item", methods="POST",
     *     requirements={"id"="^\d+$"})
     * @param int $id
     * @return JsonResponse
     */
    public function favoriteImage($id)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $repository = $entity_manager->getRepository(HistoryUploadImage::class);
        $item = $repository->find($id);
        if (!($item instanceof HistoryUploadImage)) {
            return new JsonResponse([]);
        }
        if ($item->getFavorites()) {
            $item->setFavorites(false);
        } else {
            $item->setFavorites(true);
        }
        $entity_manager->persist($item);
        $entity_manager->flush();
        return new JsonResponse([
            'status' => 'ok',
            'value' => $item->getFavorites()
        ]);
    }

    /**
     * @Route("/historyUploadImage/dropItem/{id}/",
     *     name="history_upload_image_drop_item", methods="POST",
     *     requirements={"id"="^\d+$"})
     * @param int $id
     * @return JsonResponse
     */
    public function dropImage($id)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $repository = $entity_manager->getRepository(HistoryUploadImage::class);
        $item = $repository->find($id);
        if (!($item instanceof HistoryUploadImage)) {
            return new JsonResponse([]);
        }
        $entity_manager->remove($item);
        $entity_manager->flush();
        return new JsonResponse([
            'status' => 'ok'
        ]);
    }

    /**
     * @Route("/aplDataTable/editProperty/",
     *     name="apl_data_table_edit_property", methods="POST")
     * @param Request $request
     * @return JsonResponse
     */
    public function editProperty(Request $request)
    {
        try {
            $post = $request->request;
            $class = $post->get('class');
            if (!class_exists($class)) {
                throw new Exception('Unknown entity type');
            }
            $pk = $post->get('pk');
            $id = $pk['id'];
            Validator::digit()->check($id);
            $name = $post->get('name');
            $value = $post->get('value');
            $entity_manager = $this->getDoctrine()->getManager();
            $repository = $entity_manager->getRepository($class);
            $item = $repository->find($id);
            if (!($item instanceof $class)) {
                throw new Exception('Object not found');
            }
            $setter = 'set' . ucfirst($name);
            $item->$setter($value);
            $entity_manager->persist($item);
            $entity_manager->flush();
        } catch (Throwable $exception) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
        return new JsonResponse([
            'status' => 'ok'
        ]);
    }
}
