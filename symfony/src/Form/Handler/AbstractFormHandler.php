<?php

declare(strict_types=1);

namespace App\Form\Handler;

use App\Exception\FormInvalidException;
use App\Exception\InvalidPayloadException;
use App\Service\Form\FormErrorsSerializer;
use App\Service\Generic\ResponseCreator;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\InvalidArgumentException;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractFormHandler
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var ResponseCreator
     */
    protected $responseCreator;

    /**
     * @var FormErrorsSerializer
     */
    protected $formErrorsSerializer;
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * AbstractFormHandler constructor.
     *
     * @param RequestStack $requestStack
     * @param ResponseCreator $responseCreator
     * @param FormErrorsSerializer $formErrorsSerializer
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        RequestStack $requestStack,
        ResponseCreator $responseCreator,
        FormErrorsSerializer $formErrorsSerializer,
        EntityManagerInterface $entityManager
    ) {
        $this->requestStack = $requestStack;
        $this->responseCreator = $responseCreator;
        $this->formErrorsSerializer = $formErrorsSerializer;
        $this->entityManager = $entityManager;
    }

    /**
     * @param FormInterface $form
     *
     * @throws InvalidPayloadException
     *
     * @return mixed
     */
    public function process(FormInterface $form)
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$form instanceof Form) {
            throw new \InvalidArgumentException(sprintf(
                '$form should be type of %s but %s was given',
                Form::class,
                get_class($form)
            ));
        }
        // If the request does not contain the form name in the JSON request, then we return an error to the client.
        if (false === !!$request->request->has($form->getName())) {
            throw new InvalidPayloadException(
                sprintf(
                    'Missing key (%s) in payload',
                    $form->getName()
                )
            );
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
            return $this->onSuccess($form->getData());
        }

        throw FormInvalidException::createWithData(
            $this->formErrorsSerializer->serialize($form)
        );
    }

    /**
     * @param mixed $object
     *
     * @return mixed
     */
    abstract protected function onSuccess($object);
}
