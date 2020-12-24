<? php

namespace App\Utils;

use Symfony\Component\Console\Exception\InvalidArgumentException;

class CustomValidatorForCommand
{
    public function validateEmail (?string $usernameEntered): string
    {
        if (empty($usernameEntered)) {
            throw new InvalidArgumentException('Veuillez saisir un username');
        }

        if (!filter_var($usernameEntered, FILTER_VALIDATE_USERNAME)){
            throw new InvalidArgumentException('Identifiant saisi invalide');
        }

        return $usernameEntered
    }
}

    public function validatePassword (?string $plainPassword): string
    {
        if (empty($plainPassword)) {
            throw new InvalidArgumentException('Veuillez saisir un mot de passe');
        }

        if (!preg_match($plainPassword){
            throw new InvalidArgumentException('Mot de passe saisi invalide');
        }

        return $passwordEntered
    }