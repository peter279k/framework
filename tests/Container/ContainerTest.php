<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Tests;

use Linna\Container\Container;
use Linna\Container\Exception\NotFoundException;
use Linna\TestHelper\Container\ClassACache;
use Linna\TestHelper\Container\ClassARules;
use Linna\TestHelper\Container\ClassB;
use Linna\TestHelper\Container\ClassC;
use Linna\TestHelper\Container\ClassD;
use Linna\TestHelper\Container\ClassE;
use Linna\TestHelper\Container\ClassF;
use Linna\TestHelper\Container\ClassG;
use Linna\TestHelper\Container\ClassH;
use Linna\TestHelper\Container\ClassI;
use Linna\TestHelper\Container\ClassResCache;
use Linna\TestHelper\Container\ClassResObject;
use Linna\TestHelper\Container\ClassResRules;
use PHPUnit\Framework\TestCase;

/**
 * Container test.
 */
class ContainerTest extends TestCase
{
    /**
     * Values Provider.
     *
     * @return array
     */
    public function valuesProvider(): array
    {
        return [
            ['string', 'Hello World'],
            ['int', 1],
            ['float', 1.1],
            ['array', [1, 2, 3, 4]],
            ['closure', function () {
                return 'Hello World';
            }],
            [ClassACache::class, new ClassACache('Hello World')],
        ];
    }

    /**
     * Test set and get callig method.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @dataProvider valuesProvider
     *
     * @return void
     */
    public function testSetAndGetWithMethodCall(string $key, $value): void
    {
        $container = new Container();

        $container->set($key, $value);

        $this->assertEquals($value, $container->get($key));
    }

    /**
     * Test set and get utilizing array sntax.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @dataProvider valuesProvider
     *
     * @return void
     */
    public function testSetAndGetWithArraySyntax(string $key, $value): void
    {
        $container = new Container();

        $container[$key] = $value;

        $this->assertEquals($value, $container[$key]);
    }

    /**
     * Test set and get utilizing property sntax.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @dataProvider valuesProvider
     *
     * @return void
     */
    public function testSetAndGetWithPropertySyntax(string $key, $value): void
    {
        $container = new Container();

        $container->$key = $value;

        $this->assertEquals($value, $container->$key);
    }

    /**
     * Test has callig method.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @dataProvider valuesProvider
     *
     * @return void
     */
    public function testHasWithMethodCall(string $key, $value): void
    {
        $container = new Container();

        $container->set($key, $value);

        $this->assertTrue($container->has($key));
    }

    /**
     * Test has utilizing array sntax.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @dataProvider valuesProvider
     *
     * @return void
     */
    public function testHasWithArraySyntax(string $key, $value): void
    {
        $container = new Container();

        $container[$key] = $value;

        $this->assertTrue(isset($container[$key]));
    }

    /**
     * Test has utilizing property sntax.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @dataProvider valuesProvider
     *
     * @return void
     */
    public function testHasWithWithPropertySyntax(string $key, $value): void
    {
        $container = new Container();

        $container->$key = $value;

        $this->assertTrue(isset($container->$key));
    }

    /**
     * Test delete callig method.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @dataProvider valuesProvider
     *
     * @return void
     */
    public function testDeleteUnexisting(string $key, $value): void
    {
        $this->assertFalse((new Container())->delete($key));
    }

    /**
     * Test delete callig method.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @dataProvider valuesProvider
     *
     * @return void
     */
    public function testDeleteWithMethodCall(string $key, $value): void
    {
        $container = new Container();

        $container->set($key, $value);

        $this->assertTrue($container->has($key));
        $this->assertTrue($container->delete($key));
        $this->assertFalse($container->has($key));
    }

    /**
     * Test delete utilizing array sntax.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @dataProvider valuesProvider
     *
     * @return void
     */
    public function testDeleteWithArraySyntax(string $key, $value): void
    {
        $container = new Container();

        $container[$key] = $value;

        $this->assertTrue(isset($container[$key]));

        unset($container[$key]);

        $this->assertFalse(isset($container[$key]));
    }

    /**
     * Test delete utilizing property sntax.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @dataProvider valuesProvider
     *
     * @return void
     */
    public function testDeleteWithPropertySyntax(string $key, $value): void
    {
        $container = new Container();

        $container->$key = $value;

        $this->assertTrue(isset($container->$key));

        unset($container->$key);

        $this->assertFalse(isset($container->$key));
    }

    /**
     * Test get unexisting callig method.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @dataProvider valuesProvider
     *
     * @return void
     */
    public function testGetUnexistingWithMethodCall(string $key, $value): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("No entry was found for this identifier.");

        $container = new Container();
        $container->get($key);
    }

    /**
     * Test get unexisting utilizing array sntax.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @dataProvider valuesProvider
     *
     * @return void
     */
    public function testGetUnexistingWithArraySyntax(string $key, $value): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("No entry was found for this identifier.");

        $container = new Container();
        $foo = $container[$key];
    }

    /**
     * Test get unexisting utilizing property syntax.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @dataProvider valuesProvider
     *
     * @return void
     */
    public function testGetUnexistingWithPropertySyntax(string $key, $value): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("No entry was found for this identifier.");

        $container = new Container();
        $foo = $container->$key;
    }

    /**
     * Class Provider.
     *
     * @return array
     */
    public function classProvider(): array
    {
        return [
            [ClassResObject::class],
            [ClassB::class],
            [ClassC::class],
            [ClassD::class],
            [ClassE::class],
            [ClassF::class],
            [ClassG::class],
            [ClassH::class],
            [ClassI::class],
        ];
    }

    /**
     * Test class resolving.
     *
     * @dataProvider classProvider
     *
     * @param string $class
     *
     * @return void
     */
    public function testResolve(string $class): void
    {
        $this->assertInstanceOf($class, (new Container())->resolve($class));
    }

    /**
     * Test resolving class with pre cached dependencies.
     *
     * @return void
     */
    public function testResolveWithCache(): void
    {
        $container = new Container();

        $container->set(ClassACache::class, new ClassACache('Hello World'));

        $this->assertInstanceOf(
            ClassResCache::class,
            $container->resolve(ClassResCache::class)
        );
    }

    /**
     * Test resolving class with rules
     * for unsolvable arguments.
     *
     * @return void
     */
    public function testResolveWithRules(): void
    {
        $container = new Container([
            ClassARules::class => [
                0 => true,
                2 => 'foo',
                3 => 1,
                4 => ['foo'],
                5 => 'foo',
            ],
        ]);

        $this->assertInstanceOf(
            ClassResRules::class,
            $container->resolve(ClassResRules::class)
        );
    }
}
