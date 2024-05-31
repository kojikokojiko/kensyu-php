<?php
declare(strict_types=1);
namespace App\Model;

use InvalidArgumentException;

/**
 * Class User
 *
 * Represents a user.
 *
 * @package App\Model
 */
readonly class User {
    /**
     * User constructor.
     *
     * Initializes the user with the given ID, name, email, password, and profile image.
     *
     * @param ?int $id The ID of the user, can be null.
     * @param string $name The name of the user.
     * @param string $email The email of the user.
     * @param string $password The password of the user.
     */
    public function __construct(
        public ?int $id,
        public string $name,
        public string $email,
        public string $password,
//        public ?string $profile_image = null
    ){
        $errors = $this->validate();
        if (!empty($errors)) {
            throw new InvalidArgumentException(implode(', ', $errors));
        }
    }

    /**
     * Validate user data.
     *
     * @return array An array of error messages, if any.
     */
    private function validate(): array {
        $errors = array_merge(
            $this->validateName($this->name),
            $this->validateEmail($this->email),
            $this->validatePassword($this->password)
        );

        return $errors;
    }

    /**
     * Validate the name of the user.
     *
     * @param string $name The name to validate.
     * @return array An array of error messages, if any.
     */
    private function validateName(string $name): array {
        $errors = [];

        if (trim($name) === '') {
            $errors[] = "Name is required.";
        } elseif (strlen($name) < 2 || strlen($name) > 255) {
            $errors[] = "Name must be between 2 and 255 characters.";
        }

        return $errors;
    }

    /**
     * Validate the email of the user.
     *
     * @param string $email The email to validate.
     * @return array An array of error messages, if any.
     */
    private function validateEmail(string $email): array {
        $errors = [];

        if (trim($email) === '') {
            $errors[] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        return $errors;
    }

    /**
     * Validate the password of the user.
     *
     * @param string $password The password to validate.
     * @return array An array of error messages, if any.
     */
    private function validatePassword(string $password): array {
        $errors = [];

        if (trim($password) === '') {
            $errors[] = "Password is required.";
        } elseif (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters.";
        }

        return $errors;
    }
}
?>
