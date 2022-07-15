<?php

declare(strict_types=1);

namespace GuestBook\Controllers;

use GuestBook\Database\Database;
use GuestBook\Database\DatabaseInterface;

class GuestBook implements ControllerInterface
{
    private DatabaseInterface $db;

    /**
     * @throws \Exception
     */
    public function run()
    {
        $this->db = (new Database())->connect();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->isEmailCorrect()) {
                $this->addMessage();
            }
        }
        $page = new \GuestBook\App\Page('GuestBook', 'Index');
        $page->addBlock('form');
        $page->addBlock('messages_list', [
            'messages' => $this->getMessages()
        ]);
        $page->setPropertyValue('h1', 'Гостевая книга');
        $page->setPropertyValue('title', 'Гостевая книга');
        $page->render();
    }

    /**
     * @throws \Exception
     */
    private function getMessages(): array
    {
        return $this->db->read(5);
    }

    /**
     * @throws \Exception
     */
    private function addMessage()
    {
        $this->db->add($_POST);
    }

    private function isEmailCorrect(): bool
    {
        return !empty(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL));
    }
}