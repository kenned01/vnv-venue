<?php

namespace App\Repositories;

class UserRepository extends BaseRepository
{

    public function __construct() {
        $this->table = "users";
        $this->db = new Connection();
    }
}