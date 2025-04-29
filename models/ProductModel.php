<?php

class ProductModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function insertProduct($brand, $model, $size, $color, $material, $category, $price, $img, $stock)
    {
        $stmt = $this->pdo->prepare("INSERT INTO products (brand, model, size, color, material, category, price, img, stock) VALUES (:brand, :model, :size, :color, :material, :category, :price, :img, :stock)");
        $stmt->bindParam(':brand', $brand);
        $stmt->bindParam(':model', $model);
        $stmt->bindParam(':size', $size);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':material', $material);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':img', $img);
        $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function getAllProducts()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    public function updateProduct($id, $brand, $model, $size, $color, $material, $category, $price, $stock)
    {
        $stmt = $this->pdo->prepare("UPDATE products SET brand = :brand, model = :model, size = :size, color = :color, material = :material, category = :category, price = :price, stock = :stock WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':brand', $brand);
        $stmt->bindParam(':model', $model);
        $stmt->bindParam(':size', $size);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':material', $material);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }
    

public function deleteProductById($id)
{
    try {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
        $stmt->execute();

        // Return true if a row was deleted
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        
        error_log("Error deleting product: " . $e->getMessage());
        return false;
    }
}

public function getRelatedProducts($category, $productId) {
    try {
        $stmt = $this->pdo->prepare("
            SELECT * 
            FROM products 
            WHERE category = ? AND id != ? 
            LIMIT 4
        ");
        $stmt->execute([$category, $productId]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Debugging
        error_log("Inputs: Category = $category, Product ID = $productId");
        error_log("Query Results: " . print_r($results, true));

        return $results;
    } catch (PDOException $e) {
        error_log("SQL Error: " . $e->getMessage());
        return [];
    }
}
public function updateStock($productId, $newStock)
{
    $stmt = $this->pdo->prepare("UPDATE products SET stock = :stock WHERE id = :id");
    $stmt->execute(['stock' => $newStock, 'id' => $productId]);
}

public function getAvailableSizesByModel($model)
{
    $stmt = $this->pdo->prepare("SELECT DISTINCT size FROM products WHERE model = :model ORDER BY size ASC");
    $stmt->bindParam(':model', $model);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}



}