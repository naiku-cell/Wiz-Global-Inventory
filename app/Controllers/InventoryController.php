<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\InventoryService;
use App\Models\Product;

class InventoryController
{
    public function __construct(
        private InventoryService $service
    ) {}

    /* ---------------- ADD ---------------- */
    public function add(): void
    {
        $sku = $this->service->generateSku();

        echo "Generated SKU: $sku\n";

        $name = readline("Name: ");
        $price = (float) readline("Price: ");
        $qty = (int) readline("Quantity: ");

        $this->service->add(
            new Product($sku, $name, $price, $qty)
        );

        echo "Product added successfully!\n";
    }

    /* ---------------- VIEW ---------------- */
    public function view(): void
    {
        $products = $this->service->all();

        if (empty($products)) {
            echo "No products found\n";
            return;
        }

        echo "SKU | NAME | PRICE | QTY\n";
        echo "---------------------------------\n";

        foreach ($products as $p) {
            echo "{$p->sku} | {$p->name} | {$p->price} | {$p->quantity}\n";
        }
    }

    /* ---------------- SEARCH ---------------- */
    public function search(): void
    {
        $sku = readline("Enter SKU: ");

        $p = $this->service->find($sku);

        echo $p
            ? "{$p->sku} | {$p->name} | {$p->price} | {$p->quantity}\n"
            : "SKU not found\n";
    }

    /* ---------------- UPDATE ---------------- */
    public function update(): void
    {
        $sku = readline("Enter SKU: ");
        $qty = (int) readline("New Quantity: ");

        $this->service->updateQuantity($sku, $qty);

        echo "Stock updated\n";
    }

    /* ---------------- DELETE ---------------- */
    public function delete(): void
    {
        $sku = readline("Enter SKU: ");

        $this->service->delete($sku);

        echo "Product deleted\n";
    }
}