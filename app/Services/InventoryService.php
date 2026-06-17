<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Product;

class InventoryService
{
    private array $products = [];
    private string $file;
    private string $counterFile;

    public function __construct()
    {
        $this->file = __DIR__ . "/../../storage/products.json";
        $this->counterFile = __DIR__ . "/../../storage/counter.txt";

        $this->load();
    }

    /* ---------------- LOAD ---------------- */
    private function load(): void
    {
        if (!file_exists($this->file)) {
            $this->products = [];
            return;
        }

        $data = json_decode(file_get_contents($this->file), true) ?? [];

        $this->products = array_map(fn($p) =>
            new Product(
                $p['sku'],
                $p['name'],
                $p['price'],
                $p['quantity']
            ),
            $data
        );
    }

    /* ---------------- SAVE ---------------- */
    private function save(): void
    {
        file_put_contents(
            $this->file,
            json_encode($this->products, JSON_PRETTY_PRINT)
        );
    }

    /* ---------------- SKU GENERATOR ---------------- */
    public function generateSku(): string
    {
        $count = file_exists($this->counterFile)
            ? (int) file_get_contents($this->counterFile)
            : 0;

        $count++;

        file_put_contents($this->counterFile, (string)$count);

        return "SKU-" . str_pad((string)$count, 4, "0", STR_PAD_LEFT);
    }

    /* ---------------- ADD ---------------- */
    public function add(Product $product): void
    {
        $this->products[] = $product;
        $this->save();
    }

    /* ---------------- GET ALL ---------------- */
    public function all(): array
    {
        return $this->products;
    }

    /* ---------------- FIND BY SKU ---------------- */
    public function find(string $sku): ?Product
    {
        foreach ($this->products as $p) {
            if ($p->sku === $sku) {
                return $p;
            }
        }
        return null;
    }

    /* ---------------- UPDATE BY SKU ---------------- */
    public function updateQuantity(string $sku, int $qty): void
    {
        foreach ($this->products as $p) {
            if ($p->sku === $sku) {
                $p->quantity = $qty;
                $this->save();
                return;
            }
        }
    }

    /* ---------------- DELETE BY SKU ---------------- */
    public function delete(string $sku): void
    {
        foreach ($this->products as $key => $p) {
            if ($p->sku === $sku) {
                unset($this->products[$key]);
                $this->products = array_values($this->products);
                $this->save();
                return;
            }
        }
    }
        // Find a single product inside our array using an arrow function filter
    public function findProductBySku(string $sku): ?Product {
        // array_filter walks through our internal products array list
        // The arrow function automatically captures the $sku search variable by value
        $matches = array_filter($this->products, fn(Product $p) => $p->sku === $sku);
        
        // Return the first matching product found, or null if nothing matched
        return !empty($matches) ? array_values($matches)[0] : null;
    }

}