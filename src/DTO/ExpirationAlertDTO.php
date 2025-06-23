<?php

namespace App\DTO;

class ExpirationAlertDTO extends BaseDTO
{
    public int $alertId;
    public int $productId;
    public string $message;
    public string $alertDate;
}
