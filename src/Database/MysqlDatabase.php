<?php

namespace GuestBook\Database;

class MysqlDatabase implements DatabaseInterface
{
    const TABLE_NAME = 'guest_book';

    /**
     * @var false|\mysqli
     */
    private $db;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->db = mysqli_connect($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_DATABASE'],
            $_ENV['DB_PORT']);
        if (empty($this->db)) {
            throw new \Exception('Невозможно соединиться с БД');
        }
    }

    public function add(array $fields)
    {
        $fields = $this->makeDataSafe($fields);
        if ($this->isAllFieldsFilled($fields)) {
            return mysqli_query($this->db,
                "INSERT INTO `" . self::TABLE_NAME . "` (`dtime`, `name`, `email`, `body`) VALUES (now(), '" . $fields['name'] . "', '" . $fields['email'] . "', '" . $fields['message'] . "')");
        }
        return false;
    }

    public function read(int $count = 10): array
    {
        $queryResult = $this->db->query('SELECT * FROM `' . self::TABLE_NAME . '` ORDER BY id DESC LIMIT ' . $count);
        $result = [];
        while ($ar = $queryResult->fetch_assoc()) {
            $result[] = $ar;
        }
        return $result;
    }

    private function isAllFieldsFilled(array $fields): bool
    {
        foreach ($fields as $field) {
            if (empty($field)) {
                return false;
            }
        }

        return true;
    }

    private function makeDataSafe(array $fields): array
    {
        foreach ($fields as $name => $value) {
            $fields[$name] = trim(mysqli_real_escape_string($this->db, $value));
        }
        return $fields;
    }
}