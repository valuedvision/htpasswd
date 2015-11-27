<?php
/**
 * @package axy\htpasswd
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\htpasswd;

/**
 * User in htpasswd file
 */
class User
{
    /**
     * The constructor
     *
     * @param string $name
     * @param string $hash [optional]
     */
    public function __construct($name, $hash = null)
    {
        $this->name = $name;
        $this->hash = $hash;
    }

    /**
     * Returns the content of a htpasswd file line
     *
     * @return string
     */
    public function getFileLine()
    {
        return $this->name.':'.$this->hash;
    }

    /**
     * Checks a password for the user
     *
     * @param string $password
     * @return bool
     */
    public function verify($password)
    {
        return Crypt::verify($password, $this->hash);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getFileLine();
    }

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $hash;
}
