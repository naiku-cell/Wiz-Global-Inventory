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

    /* ---------------- ADD ----]------------ */
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
    //search for the branch
        public function handleSearchProduct(): void {
        echo "\n--- Search Product by SKU ---\n";
        $sku = trim(readline("Enter SKU to search: "));

        // Call the search logic we just added to the service
        $product = $this->inventoryService->findProductBySku($sku);

        if ($product === null) {
            echo " Product with SKU '{$sku}' not found.\n";
            return;
        }

        echo " Found: {$product->name} | Price: Ksh {$product->price} | Qty: {$product->stock}\n";
    }
    
   // update for the branch
         public function handleAdjustStock(): void {
        echo "\n--- Update Product Stock Quantity ---\n";
        $sku = trim(readline("Enter Product SKU: "));
        $amount = (int) trim(readline("Enter the NEW total quantity amount: "));

        try {
            // Capture the true/false result from the service layer
            $success = $this->service->updateQuantity($sku, $amount);

            if ($success) {
                echo " Success: Stock quantity updated and database saved successfully!\n";
            } else {
                echo " Error: Product with SKU '{$sku}' not found in the database.\n";
            }
        } catch (\Exception $e) {
            echo " " . $e->getMessage() . "\n";
        }
    }

    //delete for branch
       public function handleDeleteProduct(): void {
        echo "\n--- Delete Product From Inventory ---\n";
        
        // Trim and remove any accidental spaces in the entered SKU
        $sku = str_replace(' ', '', trim(readline("Enter SKU of product to DELETE: ")));
        
        // Double check with the user before performing a permanent deletion
        $confirm = strtolower(trim(readline("Are you absolutely sure? (yes/no): ")));
        
        if ($confirm !== 'yes' && $confirm !== 'y') {
            echo " Operation cancelled. Product safe.\n";
            return;
        }

        // Trigger the deletion logic inside the service layer
        $success = $this->service->deleteProductBySku($sku);

        if ($success) {
            echo " Success: Product with SKU '{$sku}' deleted and database updated!\n";
        } else {
            echo " Error: Product with SKU '{$sku}' not found in the database.\n";
        }
    }
    //Low Stock Alerts

     public function handleLowStockAlerts(): void {
        echo "\n --- Low Stock Alerts (Less than 1 unit) --- \n";
        $lowStockItems = $this->service->getLowStockProducts();

        if (empty($lowStockItems)) {
            echo "All warehouse stock levels are healthy! No alerts.\n";
            return;
        }

        foreach ($lowStockItems as $item) {
            // Adjust the property name here ($item->quantity) to match your real Product model property
            echo " ALERT | SKU: {$item->sku} | Name: {$item->name} | Only {$item->quantity} left! (Price: Ksh {$item->price})\n";
        }
    }
}