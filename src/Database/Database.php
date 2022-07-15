<?php
declare(strict_types=1);

namespace GuestBook\Database;

class Database
{
    protected string $storageName;
    protected string $storageUser;
    protected string $storagePassword;
    protected string $storagePort;
    protected string $storageHost;

    public function __construct()
    {
        $this->storageName = $_ENV['DB_DATABASE'];
        $this->storageUser = $_ENV['DB_USERNAME'];
        $this->storagePassword = $_ENV['DB_PASSWORD'];
        $this->storagePort = $_ENV['DB_PORT'];
        $this->storageHost = $_ENV['DB_HOST'];
    }

    /**
     * @throws \Exception
     */
    public function connect(): DatabaseInterface
    {
        switch ($_ENV['DB_CONNECTION']) {
            case 'mysql':
                return new MysqlDatabase();
            case 'sqlite':
                return new SqliteDatabase();
            default:
                throw new \Exception('Error DB type. Check your .env config.');
        }
    }
}