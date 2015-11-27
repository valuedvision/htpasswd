<?php
/**
 * @package axy\htpasswd
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\htpasswd\tests;

use axy\htpasswd\User;

/**
 * coversDefaultClass axy\htpasswd\User
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * covers ::getFileLine
     */
    public function testGetFileLine()
    {
        $user = new User('nick', 'qq0e00d');
        $this->assertSame('nick:qq0e00d', $user->getFileLine());
        $this->assertSame('nick:qq0e00d', (string)$user);
    }
}
