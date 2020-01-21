<?php

namespace App\Controller;

use App\Exception\ModelValidationException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class BaseController extends AbstractFOSRestController
{
    protected function handleErrors(ConstraintViolationListInterface $validationErrors): void
    {
        if ($validationErrors->count() === 0) {
            return;
        }

        $errors = [];
        /** @var ConstraintViolation $error */
        foreach ($validationErrors as $error) {
            $errors[$error->getPropertyPath()] = $error->getMessage();
        }

        throw new ModelValidationException($errors);
    }
}

