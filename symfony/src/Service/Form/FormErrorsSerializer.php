<?php

declare(strict_types=1);

namespace App\Service\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

class FormErrorsSerializer
{
    /**
     * @var array
     */
    protected $errors;

    /**
     * @param Form $form
     *
     * @return array
     */
    public function serialize(Form $form): array
    {
        $this->errors[$form->getName()] = $this->serializeErrors($form);

        return $this->errors;
    }

    /**
     * @param Form $form
     *
     * @return array
     */
    private function serializeErrors(FormInterface $form): array
    {
        $localErrors = [
            'global' => [],
            'fields' => [],
        ];

        foreach ($form as $formField => $childForm) {
            $hasChildren = \count($childForm);

            $entryType = $childForm->getConfig()->getOption('entry_type');

            /** @var Form */
            foreach ($childForm->getErrors() as $error) {
                // check if configured field is a collection
                if ($entryType) {
                    if ($hasChildren) {
                        $localErrors['fields'][$formField][] = $error->getMessage();
                    } else {
                        $localErrors['fields'][$formField]['global'][] = $error->getMessage();
                    }
                } else {
                    $localErrors['fields'][$formField][] = $error->getMessage();
                }
            }

            if ($hasChildren > 0) {
                $localErrors['fields'][$formField] = $this->serializeErrors($childForm);
            }
        }

        foreach ($form->getErrors() as $error) {
            /** @var FormError $error */
            $localErrors['global'][] = $error->getMessage();
        }

        return $localErrors;
    }
}
