<?php

namespace App\Enum;

use App\Utils\BaseEnum;


class ProductEnum extends BaseEnum
{
    public const featured = 1;
    public const noFeatured = 0;

    public const USD = "USD";
    public const EUR = "EUR";
   
}
