<?php
class OrderModel{

    public function __construct($pdo){
        $this->pdo = $pdo;
    }
//create an aorder and return its ID
    // public function createOrder(int $userId, float $totalPrice)
    // {
    //     $stmt = $this->pdo->prepare("INSERT INTO orders (user_id, total_price, created_at) VALUES (?, ?, NOW())");
    //     $stmt->execute([$userId,$totalPrice]);
    //     return $this->pdo->lastInsertId();
    // }

    public function createOrder($userId, $totalPrice) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO orders (user_id, total_price, created_at) 
                VALUES (:user_id, :total_price, NOW())
            ");
            
            $stmt->execute([
                ':user_id' => $userId,
                ':total_price' => $totalPrice
            ]);
    
            return $this->pdo->lastInsertId(); // ✅ Возвращаем `order_id`
        } catch (PDOException $e) {
            error_log("Order Error: " . $e->getMessage());
            return false;
        }
    }
    

    //add an item to the order
    public function addOrderItem(int $orderId, int $productId, int $quantity, float $price ){
        $stmt = $this->pdo->prepare("INSERT INTO order_items (order_id, product_id,quantity, price) VALUES (?,?,?,?) ");
        $stmt->execute([$orderId,$productId,$quantity,$price]);
    }
}

?>