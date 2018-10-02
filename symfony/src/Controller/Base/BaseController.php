<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Controller\Base;

use App\Form\Handler\DefaultFormHandler;
use App\Resource\PaginationResource;
use App\Service\Generic\ResponseCreator;
use App\Service\Generic\SerializationService;
use ArrayIterator;
use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller implements BaseControllerInterface
{
    public const DELETED = ['success' => 'Deleted.'];
    public const NOT_FOUND = ['error' => 'Resource not found.'];
    public const GENERAL_ERROR = ['error' => 'Something went wrong.'];

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var DefaultFormHandler
     */
    protected $formHandler;

    /**
     * @var ResponseCreator
     */
    protected $responseCreator;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var SerializationService
     */
    protected $serializationService;

    /**
     * BaseController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param FormFactoryInterface $formFactory
     * @param DefaultFormHandler $formHandler
     * @param ResponseCreator $responseCreator
     * @param SerializerInterface $serializer
     * @param SerializationService $serializationService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory,
        DefaultFormHandler $formHandler,
        ResponseCreator $responseCreator,
        SerializerInterface $serializer,
        SerializationService $serializationService
    ) {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->formHandler = $formHandler;
        $this->responseCreator = $responseCreator;
        $this->serializer = $serializer;
        $this->serializationService = $serializationService;
    }

    /**
     * @param Pagerfanta $paginator
     *
     * @return JsonResponse
     */
    public function createCollectionResponse(PagerFanta $paginator): JsonResponse
    {

        $this->responseCreator->setCollectionData(
            $this->serialize(
                $paginator->getIterator()->getArrayCopy(),
                $this->serializationService->createBaseOnRequest()
            ),
            PaginationResource::createFromPagerfanta($paginator)
        );

        return $this->responseCreator->getResponse(Response::HTTP_OK);
    }

    /**
     * @param $resource
     * @param int $status
     * @param SerializationContext|null $context
     *
     * @return JsonResponse
     */
    public function createResourceResponse($resource, $status = Response::HTTP_OK, SerializationContext $context = null): JsonResponse
    {
        if (!$context) {
            $context = $this->serializationService->createBaseOnRequest();
        }

        $this->responseCreator->setData($this->serialize($resource, $context));

        return $this->responseCreator->getResponse($status);
    }

    /**
     * @param array $data
     * @param int $status
     *
     * @return JsonResponse
     */
    public function createSuccessfulApiResponse(array $data = [], $status = Response::HTTP_OK): JsonResponse
    {
        $this->responseCreator->setData($data);

        return $this->responseCreator->getResponse($status);
    }

    /**
     * @return JsonResponse
     */
    public function createNotFoundResponse(): JsonResponse
    {
        $this->responseCreator->setData(self::NOT_FOUND);

        return $this->responseCreator->getResponse(Response::HTTP_NOT_FOUND);
    }

    /**
     * @return JsonResponse
     */
    public function createGenericErrorResponse(): JsonResponse
    {
        $this->responseCreator->setData(self::GENERAL_ERROR);

        return $this->responseCreator->getResponse(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Request $request
     * @param Query $query
     *
     * @return Pagerfanta
     */
    public function createPaginator(Request $request, Query $query): Pagerfanta
    {
        //  Construct the doctrine adapter using the query.
        $adapter = new DoctrineORMAdapter($query);
        $paginator = new Pagerfanta($adapter);

        //  Set pages based on the request parameters.
        $paginator->setMaxPerPage($request->query->get('limit', 10));
        $paginator->setCurrentPage($request->query->get('page', 1));

        return $paginator;
    }

    /**
     * {@internal}.
     *
     * @since Entities are messed up as hell!
     *
     * @param string $entityClassName
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder(string $entityClassName): QueryBuilder
    {
        $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select('this')
            ->from($entityClassName, 'this');

        return $queryBuilder;
    }

    /**
     * @param Request $request
     * @param string $className
     * @param string $filterForm
     *
     * @throws \ReflectionException
     *
     * @return Pagerfanta
     */
    public function handleFilterForm(Request $request, string $className, string $filterForm): Pagerfanta
    {
        $queryBuilder = $this->getQueryBuilder($className);

        $form = $this->getForm($filterForm);

        if ($request->query->has($form->getName())) {
            $form->submit($request->query->get($form->getName()));

            $queryBuilder = $this->get('lexik_form_filter.query_builder_updater')
                ->addFilterConditions($form, $queryBuilder);
        }

        $paginagor = $this->createPaginator($request, $queryBuilder->getQuery());

        return $paginagor;
    }

    /**
     * @param string $type
     * @param null|array $data
     * @param array $options
     *
     * @throws \ReflectionException
     *
     * @return FormInterface
     */
    public function getForm(string $type, $data = null, array $options = []): FormInterface
    {
        $session = $this->get('session');
        $inflector = new Inflector();

        $reflectionClass = new \ReflectionClass($type);
        $name = str_replace('FormType', '', $reflectionClass->getShortName());
        $options = array_merge($options, ['csrf_protection' => false, 'allow_extra_fields' => true]);

        if ($formData = $session->get($type, null)) {
            $data = $this->serializer
                ->deserialize($formData->getJson(), $formData->getClassName(), 'json');
        }

        $form = $this->formFactory
            ->createNamedBuilder($inflector->tableize($name), $type, $data, $options)
            ->getForm();

        return $form;
    }

    /**
     * @param array $data
     * @param SerializationContext $context
     *
     * @return array
     */
    protected function serialize($data, SerializationContext $context = null): array
    {
        if ($this->serializer instanceof Serializer) {
            return $this->serializer->toArray($data, $context);
        }

        throw new \InvalidArgumentException(
            sprintf(
                'Serializer must be instance of %, but %s given',
                Serializer::class,
                get_class($this->serializer)
            )
        );
    }
}
