<?php
namespace App\Service;

use App\Exception\ValidationException;

class ValidCreateUserClient
{
    static public function checkValue($errors)
    {
        if (count($errors)) {
            $errMsg = array('ERROR IN DATA !');
            foreach ($errors as $violation) {
                $errMsg[$violation->getPropertyPath()] = $violation->getInvalidValue() . " " . $violation->getMessageTemplate().' ';
            }
            throw new ValidationException(json_encode($errMsg));
        }
        return true;
    }
}
