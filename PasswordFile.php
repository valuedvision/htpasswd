<?php
/**
 * @package axy\htpasswd
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\htpasswd;

/**
 * Htpasswd file wrapper
 */
class PasswordFile
{
    const ALG_MD5 = 'md5';
    const ALG_SHA1 = 'sha1';
    const ALG_PLAIN = 'plain';

    /**
     * The constructor
     *
     * @param string $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Returns the file name
     */
    public function getFileName()
    {
        return $this->filename;
    }

    /**
     * @var string
     */
    private $filename;
}
