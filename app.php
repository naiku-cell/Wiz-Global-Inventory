<?php
declare(strict_types=1);

require __DIR__ . "/app/Models/Product.php";
require __DIR__ . "/app/Services/InventoryService.php";
require __DIR__ . "/app/Controllers/InventoryController.php";

use App\Services\InventoryService;
use App\Controllers\InventoryController;

/* ---------------- HELPERS ---------------- */
function clearScreen(): void
{
    if (PHP_OS_FAMILY === 'Windows') {
        system('cls');
    } else {
        system('clear');
    }
}

function pause(): void
{
    readline("\nPress Enter to continue...");
}

/* ---------------- INIT ---------------- */
$controller = new InventoryController(new InventoryService());

while (true) {

    clearScreen();

    echo "===== INVENTORY SYSTEM =====\n";
    echo "1. Add Product\n";
    echo "2. View Products\n";
    echo "3. Search Product (SKU)\n";
    echo "4. Update Quantity (SKU)\n";
    echo "5. Delete Product (SKU)\n";
    echo "6. Low Stock Alerts\n";
    echo "7. Exit\n";

    $choice = readline("\nEnter choice: ");

    clearScreen();

    switch ($choice) {

        case "1":
            echo "=== ADD PRODUCT ===\n";
            $controller->add();
            pause();
            break;

        case "2":
            echo "=== ALL PRODUCTS ===\n";
            $controller->view();
            pause();
            break;

        case "3":
            echo "=== SEARCH PRODUCT ===\n";
            $controller->handleSearchProduct();
            pause();
            break;

        case "4":
            echo "=== UPDATE PRODUCT ===\n";
            $controller->handleAdjustStock();
            pause();
            break;

        case "5":
            echo "=== DELETE PRODUCT ===\n";
            $controller->handleDeleteProduct();
            pause();
            break;

             case "6":
            echo "=== Low Stock Alert ===\n";
            $controller->handleLowStockAlerts();
            pause();
            break;

        case "7":
            echo "Goodbye!\n";
            exit;

        default:
            echo "Invalid choice\n";
            pause();
    }
}