<?php

namespace WeavingTheWeb\Bundle\UserBundle\Exception;

class InvalidRoleException extends \RuntimeException
{
    const MESSAGE = 'Sorry, this role is invalid (Please check all fixtures have been loaded into the database).';
}
