<?php
/**
 * @package axy\htpasswd
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\htpasswd;

use axy\htpasswd\io\IFile;
use axy\htpasswd\io\Real;
use axy\htpasswd\errors\InvalidFileFormat;

/**
 * Htpasswd file wrapper
 */
class GroupFile
{
    const PREFIX = 'GroupName:';

    /**
     * @var string
     */
    private $filename;

    /**
     * @var \axy\htpasswd\io\IFile
     */
    private $io;

    /**
     * @var array
     */
    private $users;

    /**
     * The constructor
     *
     * @param mixed $filename [optional]
     */
    public function __construct($filename = null)
    {
        if ($filename instanceof IFile) {
            $this->io = $filename;
        } else {
            $this->filename = $filename;
            $this->io = new Real($filename);
        }
    }

    /**
     * Returns the file name
     */
    public function getFileName()
    {
        return $this->filename;
    }

    /**
     * Saves the user list to the file
     *
     * @throws \axy\htpasswd\errors\InvalidFileFormat
     * @throws \axy\htpasswd\errors\FileNotSpecified
     */
    public function save()
    {
        $this->io->save($this->getContent());
    }

    /**
     * Returns the content of the file
     *
     * @throws \axy\htpasswd\errors\InvalidFileFormat
     */
    public function getContent()
    {
        $this->load();
        return self::PREFIX . ' ' . implode(' ', $this->users);
    }

    /**
     * Checks if a user exist in the file
     *
     * @param string $user
     * @return bool
     */
    public function isUserExist($user)
    {
        $this->load();
        return isset($this->users[$user]);
    }

    /**
     * Gets all users
     *
     * @return array
     */
    public function getUsers()
    {
        $this->load();
        return $this->users;
    }

    /**
     * Add a user
     *
     * @param string $user
     * @return bool
     *         the user has been created
     * @throws \axy\htpasswd\errors\InvalidFileFormat
     */
    public function addUser($user)
    {
		if (ctype_alnum($user)) {
			$this->load();
			$new = !isset($this->users[$user]);
			if ($new) {
				array_push($this->users, $user);
			}
			return $new;
		}
		return false;
    }

    /**
     * Removes a user from the file
     *
     * @param string $user
     * @return bool
     *         the user existed and has been removed
     */
    public function remove($user)
    {
        $this->load();
        if (!isset($this->users[$user])) {
            return false;
        }
        unset($this->users[$user]);
        return true;
    }

    /**
     * Sets new filename
     *
     * @param string $filename
     */
    public function setFileName($filename)
    {
        $this->load();
        $this->filename = $filename;
        $this->io = new Real($filename);
    }

    /**
     * Loads a user list from group file
     *
     * @return null
     * @throws \axy\htpasswd\errors\InvalidFileFormat
     */
    private function load()
    {
        if ($this->users) {
            return null;
        }
        $content = $this->io->load();
        $this->users = [];
        foreach (explode("\n", $content) as $line) {
            $line = trim($line);
            if ($line !== '') {
				if (strpos($line, self::PREFIX) === 0) {
					$line = trim(str_replace(self::PREFIX, '', $line));
					if (strlen($line) > 0) {
						$this->users = explode(' ', $line);
					} else {
						return null;
					}
				} else {
					return $this->invalid();
				}
            }
        }
        return null;
    }

    /**
     * @return null
     * @throws \axy\htpasswd\errors\InvalidFileFormat
     */
    private function invalid()
    {
        $filename = $this->filename;
        if (!is_string($filename)) {
            $filename = null;
        }
        throw new InvalidFileFormat($filename);
    }
}
