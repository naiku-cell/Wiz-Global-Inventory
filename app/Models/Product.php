<?php
declare (strict_types = 1);

namespace App\Models;

class Product {
public function __construct(
    public string $sku,
    public string $name,
    public float $price,
    public int $quantity
){}
}