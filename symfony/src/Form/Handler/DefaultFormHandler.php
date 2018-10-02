<?php

declare(strict_types=1);

namespace App\Form\Handler;

class DefaultFormHandler extends AbstractFormHandler
{
    /**
     * @param mixed $object
     *
     * @return mixed
     */
    protected function onSuccess($object)
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();

        return $object;
    }
}
