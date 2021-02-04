<?php

namespace Database\Exceptions;


class InvalidArgumentException extends \Exception
{
    /**
     * InvalidArgument constructor.
     *
     * @param string $message
     */
    public function __construct($message = "") {
        parent::__construct($message);
    }
}