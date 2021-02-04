<?php


namespace Database\Exceptions;


class BuilderException extends \Exception
{
    /**
     * BuilderException constructor.
     *
     * @param string $message
     */
    public function __construct($message = "") {
        parent::__construct($message);
    }
}