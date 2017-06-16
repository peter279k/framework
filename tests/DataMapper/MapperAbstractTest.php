<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Auth\Password;
use Linna\Auth\User;
use Linna\Foo\Mappers\UserMapper;
use Linna\Storage\StorageFactory;
use PHPUnit\Framework\TestCase;

/**
 * Mapper Abstract Test
 */
class MapperAbstractTest extends TestCase
{
    /**
     * @var UserMapper The user mapper 
     */
    protected $mapper;

    /**
     * Setup.
     */
    public function setUp()
    {
        $options = [
            'dsn'      => $GLOBALS['pdo_mysql_dsn'],
            'user'     => $GLOBALS['pdo_mysql_user'],
            'password' => $GLOBALS['pdo_mysql_password'],
            'options'  => [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_PERSISTENT         => false,
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
            ],
        ];

        $this->mapper = new UserMapper(
            (new StorageFactory('pdo', $options))->getConnection(), 
            new Password()
        );
    }

    /**
     * Test create new object instance.
     */
    public function testNewObjectInstance()
    {
        $this->assertInstanceOf(UserMapper::class, $this->mapper);
    }

    /**
     * Test create new domain object with mapper.
     */
    public function testCreateDomainObjectWithMapper()
    {
        $this->assertInstanceOf(User::class, $this->mapper->create());
    }

    /**
     * Test save domain object with mapper.
     */
    public function testSaveDomainObjectWithMapper()
    {
        $user = $this->mapper->create();
        $user->name = 'test';

        $this->assertEquals('insert', $this->mapper->save($user));
    }

    /**
     * Test update domain object with mapper.
     */
    public function testUpdateDomainObjectWithMapper()
    {
        $user = $this->mapper->fetchById(1);

        $this->assertEquals(1, $user->getId());
        $this->assertEquals('root', $user->name);

        $this->assertEquals('update', $this->mapper->save($user));
    }

    /**
     * Test delete domain object with mapper.
     */
    public function testDeleteDomainObjectWithMapper()
    {
        $user = $this->mapper->fetchById(1);

        $this->assertEquals(1, $user->getId());
        $this->assertEquals('root', $user->name);

        $this->assertEquals('delete', $this->mapper->delete($user));
    }
}
