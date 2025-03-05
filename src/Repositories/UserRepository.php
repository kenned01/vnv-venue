<?php

namespace App\Repositories;

class UserRepository extends BaseRepository
{

    public function __construct() {
        $this->table = "user";
        $this->db = new Connection();
    }
}