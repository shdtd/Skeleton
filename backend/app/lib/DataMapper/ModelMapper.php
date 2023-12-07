<?php
/**
 * Description
 *
 * PHP version 8.2.5
 *
 * @category Skeleton
 * @package  DataMapper
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Libraries\DataMapper;

use Libraries\Registry;

abstract class ModelMapper
{
    use ModelModules;

    /**
     * Description
     *
     * @var int ALL_COLUMN = 0
     */
    public const ALL_COLUMN = 0;

    /**
     * Description
     *
     * @var int PROTECTED_COLUMN = 1
     */
    public const PROTECTED_COLUMN = 1;

    /**
     * Description
     *
     * @var int PUBLIC_COLUMN = 2
     */
    public const PUBLIC_COLUMN = 2;

    /**
     * Description
     *
     * @var \PDO $pdo
     */
    protected \PDO $pdo;

    /**
     * Description
     *
     * @var \PDOStatement $findByIdStmt
     */
    protected \PDOStatement $findByIdStmt;

    /**
     * Description
     *
     * @var \PDOStatement $selectStmt
     */
    protected \PDOStatement $selectStmt;

    /**
     * Description
     *
     * @var \PDOStatement $updateStmt
     */
    protected \PDOStatement $updateStmt;

    /**
     * Description
     *
     * @var \PDOStatement $updateWithoutPasswordStmt
     */
    protected \PDOStatement $updateWithoutPasswordStmt;

    /**
     * Description
     *
     * @var \PDOStatement $insertStmt
     */
    protected \PDOStatement $insertStmt;

    /**
     * Description
     *
     * @var \PDOStatement $deleteStmt
     */
    protected \PDOStatement $deleteStmt;

    /**
     * Description
     *
     * @var string $tableName
     */
    protected string $tableName;

    /**
     * Description
     *
     * @var string $primaryKey
     */
    protected string $primaryKey;

    /**
     * Description
     *
     * @var string $defaultOrderBy
     */
    protected string $defaultOrderBy = 'created_at';

    /**
     * Description
     *
     * @var array<int,string> $columns
     */
    protected array $columns = [];

    /**
     * Description
     *
     * @var array<int,string> $columnsProtected
     */
    protected array $columnsProtected = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pdo = Registry::instance()->getPdo();
        $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        $this->init();
        $this->genStmt();
    }

    /**
     * Description
     *
     * @return void
     */
    abstract protected function init(): void;

    /**
     * Description
     *
     * @return void
     */
    protected function genStmt(): void
    {
        $this->findByIdStmt = $this->pdo->prepare(
            'SELECT * FROM ' .
            $this->tableName .
            ' WHERE "' .
            $this->primaryKey .
            '"=?'
        );

        $this->selectStmt = $this->pdo->prepare(
            'SELECT * FROM ' .
            $this->tableName .
            ' ORDER BY "' .
            $this->defaultOrderBy .
            '"'
        );

        $columnsPublic  = array_diff($this->columns, $this->columnsProtected);
        $insertQuery    = 'INSERT INTO ' . $this->tableName . ' (';
        $insertSubQuery = '';
        $updateQuery    = 'UPDATE ' . $this->tableName . ' SET';
        $updateWithoutPasswordQuery = 'UPDATE ' . $this->tableName . ' SET';

        foreach ($columnsPublic as $column) {
            $insertQuery    .= '"' . $column . '", ';
            $insertSubQuery .= '?, ';
            $updateQuery    .= ' "' . $column . '"=?,';

            if ($column !== 'password') {
                $updateWithoutPasswordQuery .= ' "' . $column . '"=?,';
            }
        }

        $insertQuery = mb_substr($insertQuery, 0, -2) .
            ') VALUES (' .
            mb_substr($insertSubQuery, 0, -2) .
            ')';

        $updateQuery = mb_substr($updateQuery, 0, -1) .
            ' WHERE "' .
            $this->primaryKey .
            '"=?';

        $updateWithoutPasswordQuery = mb_substr(
            $updateWithoutPasswordQuery,
            0,
            -1
        ) . ' WHERE "' . $this->primaryKey . '"=?';

        $this->updateStmt = $this->pdo->prepare(
            $updateQuery
        );

        $this->updateWithoutPasswordStmt = $this->pdo->prepare(
            $updateWithoutPasswordQuery
        );

        $this->insertStmt = $this->pdo->prepare(
            $insertQuery
        );

        $this->deleteStmt = $this->pdo->prepare(
            'DELETE FROM ' .
            $this->tableName .
            ' WHERE "' .
            $this->primaryKey .
            '"=?'
        );
    }

    /**
     * Description
     *
     * @param integer $type Description.
     *
     * @return array<string>
     */
    public function getColumns(int $type = self::ALL_COLUMN): array
    {
        switch ($type) {
            case self::PROTECTED_COLUMN:
                return $this->columnsProtected;

            case self::PUBLIC_COLUMN:
                return array_diff($this->columns, $this->columnsProtected);

            default:
                return $this->columns;
        }
    }

    /**
     * Description
     *
     * @return ModelCollection
     */
    public function select(): ModelCollection
    {
        $this->selectStmt->execute();
        $object = Registry::instance()->getModelCollection($this);

        while (($row = $this->selectStmt->fetch(\PDO::FETCH_ASSOC)) !== false) {
            $object->add($this->createObject($row));
        }

        $this->selectStmt->closeCursor();
        return $object;
    }

    /**
     * Description
     *
     * @param integer $id Description.
     *
     * @return Model|null
     */
    public function findByID(int $id): Model|null
    {
        $old = $this->getFromMap($id);

        if (isset($old) === true) {
            return $old;
        }

        $this->findByIdStmt->execute([$id]);
        $row = $this->findByIdStmt->fetch();
        $this->findByIdStmt->closeCursor();

        if (is_array($row) === false) {
            return null;
        }

        if (isset($row[$this->primaryKey]) === false) {
            return null;
        }

        $object = $this->createObject($row);
        return $object;
    }

    /**
     * Description
     *
     * @param array<string,int|string> $raw Description.
     *
     * @return Model
     */
    public function createObject(array $raw): Model
    {
        $old = $this->getFromMap((int) ($raw[$this->primaryKey] ?? -1));

        if (isset($old) === true) {
            return $old;
        }

        $row = [];

        array_map(
            function ($val) use ($raw, &$row) {
                $row[$val] = ($raw[$val] ?? null);
            },
            $this->columns
        );

        $obj = new Model($row);
        $this->addToMap($obj);

        return $obj;
    }

    /**
     * Description
     *
     * @param Model $obj Description.
     *
     * @return boolean
     */
    public function insert(Model $obj): bool
    {
        $values = array_map(
            function ($val) use ($obj) {
                return $obj->get($val);
            },
            [...array_diff($this->columns, $this->columnsProtected)]
        );

        $success = $this->insertStmt->execute($values);
        $id      = (int) $this->pdo->lastInsertId();
        $obj->set($this->primaryKey, $id);
        $this->addToMap($obj);

        return $success;
    }

    /**
     * Description
     *
     * @param integer $id Description.
     *
     * @return Model|null
     */
    private function getFromMap(int $id): Model|null
    {
        return ModelWatcher::exists($this::class, $id);
    }

    /**
     * Description
     *
     * @param Model $obj Description.
     *
     * @return void
     */
    private function addToMap(Model $obj): void
    {
        ModelWatcher::add($obj);
    }

    /**
     * Description
     *
     * @param Model $object Description.
     *
     * @return boolean
     */
    public function update(Model $object): bool
    {
        $values = array_map(
            function ($val) use ($object) {
                return $object->get($val);
            },
            [...array_diff($this->columns, $this->columnsProtected)]
        );

        $values[] = $object->get($this->primaryKey);

        return $this->updateStmt->execute($values);
    }

    /**
     * Description
     *
     * @param Model $object Description.
     *
     * @return boolean
     */
    public function updateWithoutPassword(Model $object): bool
    {
        $colProtect   = $this->columnsProtected;
        $colProtect[] = 'password';
        $values       = array_map(
            function ($val) use ($object) {
                return $object->get($val);
            },
            [...array_diff($this->columns, $colProtect)]
        );

        $values[] = $object->get($this->primaryKey);

        return $this->updateWithoutPasswordStmt->execute($values);
    }

    /**
     * Description
     *
     * @param integer $id Description.
     *
     * @return boolean
     */
    public function delete(int $id): bool
    {
        return $this->deleteStmt->execute([$id]);
    }

    /* WILL DO IT LATER
    abstract protected function selectAllStmt(): \PDOStatement; */

    /**
     * Description
     *
     * @param array<int,array<string,int|string>> $raw Description.
     *
     * @return ModelCollection
     */
    protected function getCollection(array $raw = []): ModelCollection
    {
        return new ModelCollection($this, $raw);
    }
}
