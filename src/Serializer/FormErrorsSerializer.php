<?php

namespace App\Serializer;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Serializes invalid Form instances.
 */
class FormErrorsSerializer
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function convertFormToArray(FormInterface $data): array
    {
        $form = $errors = [];

        foreach ($data->getErrors() as $error) {
            $errors[] = $this->getErrorMessage($error);
        }

        if ($errors) {
            $form['errors'] = $errors;
        }

        $children = [];
        foreach ($data->all() as $child) {
            if ($child instanceof FormInterface) {
                $children[$child->getName()] = $this->convertFormToArray($child);
            }
        }

        if ($children) {
            $form['children'] = $children;
        }

        return $form;
    }

    private function getErrorMessage(FormError $error): string
    {
        if (null !== $error->getMessage()) {
            return $this->translator->trans(
                $error->getMessage(),
                $error->getMessageParameters(),
                'validators'
            );
        }

        return $this->translator->trans($error->getMessageTemplate(), $error->getMessageParameters(), 'validators');
    }
}
