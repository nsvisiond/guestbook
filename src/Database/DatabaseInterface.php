<?php

namespace GuestBook\Database;

interface DatabaseInterface
{
    public function add(array $fields);
    public function read(int $count): array;
}