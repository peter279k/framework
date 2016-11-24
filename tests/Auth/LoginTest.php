<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

declare(strict_types=1);

use Linna\Session\Session;
use Linna\Auth\Password;
use Linna\Auth\Login;
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    protected $session;
    protected $password;
    protected $login;
    
    public function setUp()
    {
        $session = new Session();
        $password = new Password();
        
        $this->login = new Login($session, $password);
        $this->password = $password;
        $this->session = $session;
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testLogin()
    {
        $this->session->start();
        
        //hash password
        $storedPassword = $this->password->hash('password');
        
        //attemp first login
        $loginResult = $this->login->login('root', 'password', $storedUser = 'root', $storedPassword, 1);
        
        //attemp check if logged
        $logged = $this->login->logged;
        
        //simulate expired login
        $this->session->loginTime = time() - 3600;
        
        //attemp second login
        $secondLogin = new Login($this->session, $this->password);
        $notLogged = $secondLogin->logged;
        
        $this->assertEquals(true, $loginResult);
        $this->assertEquals(true, $logged);
        $this->assertEquals(false, $notLogged);
        
        $this->session->destroy();
    }
    
    /**
     * @runInSeparateProcess
     */ 
    public function testLogout()
    {
        $this->session->start();
        
        //hash password
        $storedPassword = $this->password->hash('password');
        
        //attemp first login
        $this->login->login('root', 'password', $storedUser = 'root', $storedPassword, 1);
        $loginResult = $this->login->logged;
        
        //do logout
        $this->login->logout();
        
        //create new login instance
        $login = new Login($this->session, $this->password);
        $noLoginResult = $login->logged;
        
        $this->assertEquals(true, $loginResult);
        $this->assertEquals(false, $noLoginResult);
        
        $this->session->destroy();
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testIncorrectLogin()
    {
        $this->session->start();
        
         //hash password
        $storedPassword = $this->password->hash('password');
        
        //try login with bad credentials
        $loginResult = $this->login->login('root', 'badPassword', $storedUser = 'root', $storedPassword, 1);
        $loginResult2 = $this->login->login('badUser', 'password', $storedUser = 'root', $storedPassword, 1);
        
        $this->assertEquals(false, $loginResult);
        $this->assertEquals(false, $loginResult2);
        
        $this->session->destroy();
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testLoginRefreshTrue()
    {
        $this->session->start();
        
        //hash password
        $storedPassword = $this->password->hash('password');
        
        //attemp first login
        $login = new Login($this->session, $this->password);
        $loginResult = $login->login('root', 'password', $storedUser = 'root', $storedPassword, 1);
        
        //attemp check if logged
        $logged = $login->logged;
        
        //simulate expired login
        $this->session->loginTime = time() - 3600;
        
        //attemp second login
        $secondLogin = new Login($this->session, $this->password);
        $notLogged = $secondLogin->logged;
        
        $this->assertEquals(true, $loginResult);
        $this->assertEquals(true, $logged);
        $this->assertEquals(false, $notLogged);
        
        $this->session->destroy();
    }
}
