<?php

declare(strict_types=1);

namespace App\Service\Generic;

use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\RequestStack;

class SerializationService
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * SerializationService constructor.
     *
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @return SerializationContext|null
     */
    public function createBaseOnRequest(): SerializationContext
    {
        $currentRequest = $this->requestStack->getCurrentRequest();

        if (false === !!$currentRequest) {
            throw new \RuntimeException(sprintf('Current request is is required!'));
        }

        $expand = $currentRequest
            ->query
            ->get('expand', []);

        if (true === is_string($expand)) {
            $expand = explode(',', $expand);
        }

        return $this->createWithGroups($expand);
    }

    /**
     * @param array $groups
     *
     * @return SerializationContext
     */
    public function createWithGroups(array $groups): SerializationContext
    {
        $serializationContext = SerializationContext::create();
        $serializationContext->setGroups(array_merge(['Default'], $groups));

        return $serializationContext;
    }
}
