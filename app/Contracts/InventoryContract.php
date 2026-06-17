<?php
declare(strict_types=1);

namespace App\Contracts;

use App\Models\Product;

interface InventoryContract
{
    public function add(Product $product): void;
    public function all(): array;
    public function find(string $name): ?Product;
    public function updateQuantity(string $name, int $qty): void;
    public function delete(string $name): void;
}