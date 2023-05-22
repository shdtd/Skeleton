<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Tests\DB
 * @package  UsersTest
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Tests\DB;

use PHPUnit\Framework\TestCase;

/**
 * UsersTest class
 * Description
 *
 * @category Tests\DB
 * @package  UsersTest
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */
class UsersTest extends TestCase
{

    /**
     * Description
     *
     * @var DBExtension $dbExtension
     */
    private DBExtension $dbExtension;

    /**
     * Description
     *
     * @var \PDO $db
     */
    private \PDO $db;

    /**
     * Settings for test
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->dbExtension = DBExtension::getInstance();
        $this->db          = $this->dbExtension->db;
    }

    /**
     * Description
     *
     * @return void
     */
    public function testTest(): void
    {
        $this->dbExtension->setTriggers(DBExtension::DISABLE_TRIGGERS, 'users');

        /* INSERT */
        $ret = $this->db->exec(
            'INSERT INTO users (firstname, lastname, email, password) VALUES ' .
            "('test_fname', 'test_lastname', 'test_email', 'test_password')"
        );

        $this->assertSame(
            $ret,
            1,
            "+----------------------------------------------------+\n" .
            "| The creation of a new user has not been completed. |\n" .
            "| The INSERT method returned an error.               |\n" .
            "+----------------------------------------------------+\n"
        );

        $userID = $this->db->lastInsertId();

        printf(
            "+------------------------------------+\n" .
            "| New user added. User id is: %6s |\n" .
            "+------------------------------------+\n",
            $userID
        );

        /* UPDATE */
        $stmt = $this->db->prepare(
            'UPDATE users SET firstname=?, lastname=?, email=? WHERE id=?'
        );
        $ret  = $stmt->execute(['name', 'fname', 'name@mail.com', $userID]);
        $this->assertSame(
            $ret,
            true,
            "+----------------------------------------------------+\n" .
            "| The update of a user has not been completed.       |\n" .
            "| The UPDATE method returned an error.               |\n" .
            "+----------------------------------------------------+\n"
        );

        echo "+------------------------------------+\n";
        echo "| User updated successfully.         |\n";
        echo "+------------------------------------+\n";

        /* SELECT */
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id=?');
        $stmt->execute([$userID]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertSame(
            $rows[0]['email'],
            'name@mail.com',
            "+----------------------------------------------------+\n" .
            "| The select of a user data has not been completed.  |\n" .
            "| The SELECT method returned an error.               |\n" .
            "+----------------------------------------------------+\n"
        );

        echo "+------------------------------------+\n";
        echo "| User data selected successfully.   |\n";
        echo "+------------------------------------+\n";

        echo "+--------------------------------------+\n";
        echo "| User data is:                        |\n";
        printf("| %-36s |\n", 'ID        ' . $rows[0]['id']);
        printf("| %-36s |\n", 'FIRSTNAME ' . $rows[0]['firstname']);
        printf("| %-36s |\n", 'LASTNAME  ' . $rows[0]['lastname']);
        printf("| %-36s |\n", 'EMAIL     ' . $rows[0]['email']);
        printf("| %-36s |\n", 'PASSWORD  ' . $rows[0]['password']);
        printf("| %-36s |\n", 'CREATED   ' . $rows[0]['created_at']);
        printf("| %-36s |\n", 'UPDATED   ' . $rows[0]['updated_at']);
        echo "+--------------------------------------+\n";

        /* DELETE */
        $stmt = $this->db->prepare('DELETE FROM users WHERE id=?');
        $ret  = $stmt->execute([$userID]);
        $this->assertSame(
            $ret,
            true,
            "+----------------------------------------------------+\n" .
            "| The delete of a user has not been completed.       |\n" .
            "| The DELETE method returned an error.               |\n" .
            "+----------------------------------------------------+\n"
        );

        echo "+------------------------------------+\n";
        echo "| User deleted successfully.         |\n";
        echo "+------------------------------------+\n";

        $this->dbExtension->setTriggers(DBExtension::ENABLE_TRIGGERS, 'users');
    }
}
