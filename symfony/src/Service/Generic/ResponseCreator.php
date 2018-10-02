<?php

declare(strict_types=1);

namespace App\Service\Generic;

use App\Resource\PaginationResource;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseCreator
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var SerializationService
     */
    protected $serializationService;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var PaginationResource
     */
    protected $pagination;

    /**
     * ResponseCreator constructor.
     *
     * @param SerializationService $serializationService
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializationService $serializationService, SerializerInterface $serializer)
    {
        $this->serializationService = $serializationService;
        $this->serializer = $serializer;
        $this->data = [];
        $this->pagination = null;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @param array $data
     * @param paginationResource $pagination
     */
    public function setCollectionData(array $data, PaginationResource $pagination): void
    {
        $this->data = $data;
        $this->pagination = $pagination;
    }

    /**
     * @param int $code
     *
     * @return JsonResponse
     */
    public function getResponse(int $code): JsonResponse
    {
        $context = $this->serializationService->createBaseOnRequest();

        $response = new JsonResponse(null, $code);
        $response->setContent($this->serializer->serialize($this->buildResponse(), 'json', $context));

        return $response;
    }

    protected function buildResponse(): array
    {
        $responseArray = [];

        $responseArray['data'] = $this->data ?: new \stdClass();

        if ($this->pagination) {
            $responseArray['pagination'] = $this->pagination->toJsArray();
        }

        return $responseArray;
    }
}
