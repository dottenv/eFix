<?php

namespace App\Models;

class Lead
{
    public int $id;
    public string $name;
    public string $phone;
    public ?string $email;
    public string $service_type;
    public ?string $device_model;
    public ?string $device_brand;
    public ?string $message;
    public string $status;
    public string $source;
    public string $ip;
    public string $created_at;
    public ?string $updated_at;
}
