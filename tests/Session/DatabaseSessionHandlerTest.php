<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */
use Linna\Database\Database;
use Linna\Database\MysqlPDOAdapter;
use Linna\Session\DatabaseSessionHandler;
use Linna\Session\Session;
use PHPUnit\Framework\TestCase;

class DatabaseSessionHandlerTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testSession()
    {
        $MysqlAdapter = new MysqlPDOAdapter(
                $GLOBALS['db_type'].':host='.$GLOBALS['db_host'].';dbname='.$GLOBALS['db_name'].';charset=utf8mb4',
                $GLOBALS['db_username'],
                $GLOBALS['db_password'],
                array(\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING)
        );

        $dbase = new Database($MysqlAdapter);

        $sessionHandler = new DatabaseSessionHandler($dbase);

        $session = new Session(['expire' => 8]);
        
        $session->setSessionHandler($sessionHandler);
        
        $session->start();

        $session->testdata = 'test';

        $this->assertEquals('test', $session->testdata);

        $session->testdata = 'new test';

        $this->assertEquals('new test', $session->testdata);

        unset($session->testdata);

        $this->assertEquals(false, $session->testdata);

        $session->commit();
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testExpiredSession()
    {
        $MysqlAdapter = new MysqlPDOAdapter(
                $GLOBALS['db_type'].':host='.$GLOBALS['db_host'].';dbname='.$GLOBALS['db_name'].';charset=utf8mb4',
                $GLOBALS['db_username'],
                $GLOBALS['db_password'],
                array(\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING)
        );

        $dbase = new Database($MysqlAdapter);

        $sessionHandler = new DatabaseSessionHandler($dbase);

        $session = new Session(['expire' => 8]);
        
        $session->setSessionHandler($sessionHandler);
        $session->start();
        $session_id = $session->id;
        
        $session->time = $session->time - 1800;
        
        $session->commit();
        
        $session->setSessionHandler($sessionHandler);
        $session->start();
        $session_id2 = $session->id;
        
        $test = ($session_id === $session_id2) ? 1 : 0;

        $this->assertEquals(0, $test);
        
        $session->destroy();
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testGc()
    {
        $MysqlAdapter = new MysqlPDOAdapter(
                $GLOBALS['db_type'].':host='.$GLOBALS['db_host'].';dbname='.$GLOBALS['db_name'].';charset=utf8mb4',
                $GLOBALS['db_username'],
                $GLOBALS['db_password'],
                array(\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING)
        );

        $dbase = new Database($MysqlAdapter);

        $sessionHandler = new DatabaseSessionHandler($dbase);
        
        $conn = $dbase->connect();
        
        $pdos = $conn->prepare('DELETE FROM session');
        $pdos->execute();
        
        for ($i = 0;$i<10;$i++) {
            $sessionId = md5($i);
            $time = time()-$i;
            $data = 'time|i:'.$time.';';
            
            $pdos = $conn->prepare('INSERT INTO session (session_id, session_data) VALUES (:session_id, :session_data)');

            $pdos->bindParam(':session_id', $sessionId, \PDO::PARAM_STR);
            $pdos->bindParam(':session_data', $data, \PDO::PARAM_STR);
            $pdos->execute();
        }
        
        $sessionHandler->gc(-1);
        
        $pdos = $conn->prepare('SELECT * FROM session');
        $pdos->execute();
        
        $this->assertEquals(0, $pdos->rowCount());
    }
}
