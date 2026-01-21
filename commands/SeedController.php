<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Product;
use yii\helpers\Console;

class SeedController extends Controller
{
    public function actionIndex()
    {
        $this->stdout("Seeding products...\n", Console::FG_YELLOW);

        // Limpiar tabla de productos (opcional, cuidado en producción)
        // Product::deleteAll(); 

        $products = [
            ['Laptop Dell XPS 13', 'Laptop ultradelgada con pantalla InfinityEdge', 'DELL-XPS13', 1299.99, 15],
            ['MacBook Pro 14', 'Chip M1 Pro, 16GB RAM, 512GB SSD', 'APPLE-MBP14', 1999.00, 10],
            ['Monitor Samsung Odyssey', 'Monitor curvo gaming 32 pulgadas 144Hz', 'SAMSUNG-G7', 599.50, 20],
            ['Teclado Keychron K2', 'Teclado mecánico inalámbrico 75%', 'KEYCHRON-K2', 89.00, 50],
            ['Mouse Logitech MX Master 3', 'Mouse ergonómico inalámbrico avanzado', 'LOGI-MXM3', 99.99, 45],
            ['Auriculares Sony WH-1000XM4', 'Noise cancelling, 30h batería', 'SONY-XM4', 348.00, 30],
            ['Ipad Air 5', 'Chip M1, 64GB, Wi-Fi', 'APPLE-IPAD5', 599.00, 25],
            ['Silla Ergonómica Herman Miller', 'Aeron Chair, soporte lumbar ajustable', 'HM-AERON', 1450.00, 5],
            ['Escritorio Elevable', 'Escritorio motorizado con memoria', 'DESK-MOT-1', 450.00, 12],
            ['Webcam Logitech C920', 'Webcam HD Pro 1080p', 'LOGI-C920', 79.99, 60],
        ];

        foreach ($products as $prodData) {
            $product = new Product();
            $product->name = $prodData[0];
            $product->description = $prodData[1];
            $product->sku = $prodData[2];
            $product->price = $prodData[3];
            $product->stock = $prodData[4];

            if ($product->save()) {
                $this->stdout("Created: " . $product->name . "\n", Console::FG_GREEN);
            } else {
                $this->stdout("Error creating " . $prodData[0] . ": " . json_encode($product->errors) . "\n", Console::FG_RED);
            }
        }

        $this->stdout("Seeding complete.\n", Console::FG_GREEN);
    }
}
