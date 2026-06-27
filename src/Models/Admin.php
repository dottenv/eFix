<?php

namespace App\Models;

class Admin
{
    public int $id;
    public string $username;
    public string $password_hash;
    public string $role;
    public string $created_at;
}
