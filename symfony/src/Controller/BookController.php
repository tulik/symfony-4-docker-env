<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Base\BaseController;
use App\Entity\Book;
use App\Exception\ApiException;
use App\Form\BookType;
use App\Form\Filter\BookFilterFormType;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/books")
 */
class BookController extends BaseController
{
    /**
     * Get all Books.
     *
     * @Route(name="api_book_list", methods={Request::METHOD_GET})
     *
     * @SWG\Tag(name="Book")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of books",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Book::class))
     *     )
     * )
     *
     * @param Request $request
     *
     * @throws \ReflectionException
     *
     * @return JsonResponse
     */
    public function listAction(Request $request): JsonResponse
    {
        return $this->createCollectionResponse(
            $this->handleFilterForm(
                $request,
                Book::class,
                BookFilterFormType::class
            )
        );
    }

    /**
     * Show single Books.
     *
     * @Route(path="/{book}", name="api_book_show", methods={Request::METHOD_GET})
     *
     * @SWG\Tag(name="Book")
     * @SWG\Response(
     *     response=200,
     *     description="Returns book of given identifier.",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Items(ref=@Model(type=Book::class))
     *     )
     * )
     *
     * @param Book|null $book
     *
     * @return JsonResponse
     */
    public function showAction(Book $book = null): JsonResponse
    {
        if (false === !!$book) {
            return $this->createNotFoundResponse();
        }

        return $this->createResourceResponse($book);
    }

    /**
     * Add new Book.
     *
     * @Route(name="api_book_create", methods={Request::METHOD_POST})
     *
     * @SWG\Tag(name="Book")
     * @SWG\Response(
     *     response=201,
     *     description="Creates new Book and returns the created object.",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Items(ref=@Model(type=Book::class))
     *     )
     * )
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Security("book.isOwner(user)")
     */
    public function createAction(Request $request): JsonResponse
    {
        $book = new Book();

        $form = $this->createForm(
            BookType::class, $book, [
            'method' => $request->getMethod(),
            ]
        );

        try {
            $this->formHandler->process($form);
        } catch (ApiException $e) {
            return new JsonResponse($e->getData(), Response::HTTP_BAD_REQUEST);
        }

        return $this->createResourceResponse($book);
    }

    /**
     * Edit existing Book.
     *
     * @SWG\Tag(name="Book")
     *
     * @Route(path="/{book}", name="api_book_edit", methods={Request::METHOD_PATCH})
     *
     * @SWG\Tag(name="Book")
     * @SWG\Response(
     *     response=200,
     *     description="Updates Book of given identifier and returns the updated object.",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Items(ref=@Model(type=Book::class))
     *     )
     * )
     *
     * @param Request $request
     * @param Book|null $book
     *
     * @return JsonResponse
     *
     * @Security("book.isOwner(user)")
     */
    public function updateAction(Request $request, Book $book = null): JsonResponse
    {
        if (false === !!$book) {
            return $this->createNotFoundResponse();
        }

        $form = $this->createForm(
            BookType::class, $book, [
            'method' => $request->getMethod(),
            ]
        );

        try {
            $this->formHandler->process($form);
        } catch (ApiException $e) {
            return new JsonResponse($e->getData(), Response::HTTP_BAD_REQUEST);
        }

        return $this->createResourceResponse($book);
    }

    /**
     * Delete Book.
     *
     * @Route(path="/{book}", name="api_book_delete", methods={Request::METHOD_DELETE})
     *
     * @SWG\Tag(name="Book")
     * @SWG\Response(
     *     response=200,
     *     description="Delete Book of given identifier and returns the empty object.",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Items(ref=@Model(type=Book::class))
     *     )
     * )
     *
     * @param Book|null $book
     *
     * @return JsonResponse
     *
     * @Security("book.isOwner(user)")
     */
    public function deleteAction(Book $book = null): JsonResponse
    {
        if (false === !!$book) {
            return $this->createNotFoundResponse();
        }

        $this->entityManager->remove($book);
        $this->entityManager->flush();

        return $this->createSuccessfulApiResponse(self::DELETED);
    }
}
