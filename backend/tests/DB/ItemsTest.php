<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Tests\DB
 * @package  ItemsTest
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Tests\DB;

use PHPUnit\Framework\TestCase;

/**
 * ItemsTest class
 * Description
 *
 * @category Tests\DB
 * @package  ItemsTest
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */
class ItemsTest extends TestCase
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
        $this->dbExtension->setTriggers(DBExtension::DISABLE_TRIGGERS, 'items');

        /* INSERT */
        $ret = $this->db->exec(
            'INSERT INTO items (name, phone, key) VALUES ' .
            "('test_name', 'test_phone', 'test_key')"
        );

        $this->assertSame(
            $ret,
            1,
            "+----------------------------------------------------+\n" .
            "| The creation of a new item has not been completed. |\n" .
            "| The INSERT method returned an error.               |\n" .
            "+----------------------------------------------------+\n"
        );

        $itemID = $this->db->lastInsertId();

        printf(
            "+------------------------------------+\n" .
            "| New item added. Item id is: %6s |\n" .
            "+------------------------------------+\n",
            $itemID
        );

        /* UPDATE */
        $stmt = $this->db->prepare(
            'UPDATE items SET name=?, phone=?, key=? WHERE id=?'
        );
        $ret  = $stmt->execute(['name', 'phone', 'keykeykeykey', $itemID]);
        $this->assertSame(
            $ret,
            true,
            "+----------------------------------------------------+\n" .
            "| The update of a item has not been completed.       |\n" .
            "| The UPDATE method returned an error.               |\n" .
            "+----------------------------------------------------+\n"
        );

        echo "+------------------------------------+\n";
        echo "| Item updated successfully.         |\n";
        echo "+------------------------------------+\n";

        /* SELECT */
        $stmt = $this->db->prepare('SELECT * FROM items WHERE id=?');
        $stmt->execute([$itemID]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertSame(
            $rows[0]['key'],
            'keykeykeykey',
            "+----------------------------------------------------+\n" .
            "| The select of a item data has not been completed.  |\n" .
            "| The SELECT method returned an error.               |\n" .
            "+----------------------------------------------------+\n"
        );

        echo "+------------------------------------+\n";
        echo "| Item data selected successfully.   |\n";
        echo "+------------------------------------+\n";

        echo "+--------------------------------------+\n";
        echo "| Item data is:                        |\n";
        printf("| %-36s |\n", 'ID        ' . $rows[0]['id']);
        printf("| %-36s |\n", 'NAME      ' . $rows[0]['name']);
        printf("| %-36s |\n", 'PHONE     ' . $rows[0]['phone']);
        printf("| %-36s |\n", 'KEY       ' . $rows[0]['key']);
        printf("| %-36s |\n", 'CREATED   ' . $rows[0]['created_at']);
        printf("| %-36s |\n", 'UPDATED   ' . $rows[0]['updated_at']);
        echo "+--------------------------------------+\n";

        /* DELETE */
        $stmt = $this->db->prepare('DELETE FROM items WHERE id=?');
        $ret  = $stmt->execute([$itemID]);
        $this->assertSame(
            $ret,
            true,
            "+----------------------------------------------------+\n" .
            "| The delete of a item has not been completed.       |\n" .
            "| The DELETE method returned an error.               |\n" .
            "+----------------------------------------------------+\n"
        );

        echo "+------------------------------------+\n";
        echo "| Item deleted successfully.         |\n";
        echo "+------------------------------------+\n";

        $this->dbExtension->setTriggers(DBExtension::ENABLE_TRIGGERS, 'items');
    }
}
