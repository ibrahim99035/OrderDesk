<?php
namespace App\controllers;

use App\core\View;
use App\models\Order;
use App\models\Product;
use App\models\User;
use App\models\Room;

class OrderController {
    // private function requireRole($allowedRoles): void
    // {
    //     $role = $_SESSION['role'] ?? 'user';
    //     if(!in_array($role, $allowedRoles))
    //         {
    //             header("Location: /orders/my");
    //             exit;
    //         }
    // }

    private function getIdFromUrl(): ?int
    {
        $parts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $id    = end($parts);
        return is_numeric($id) ? (int)$id : null;
    }

        private function findOrFail(int $orderId, string $redirect = "/orders"): array
    {
        $order    = new Order();
        $existing = $order->find($orderId);
        if (!$existing) {
            $_SESSION['error'] = "the order $orderId isn't found ";
            header("Location: $redirect");
            exit;
        }
        return $existing;
    }

    private function redirectBack(): void
    {
        $role = $_SESSION['role'] ?? 'user';
        if (in_array($role, ['admin', 'office_boy'])) {
            header("Location: /orders");
        } else {
            header("Location: /orders/my");
        }
    }
    private function attachItems(array &$orders): void
    {
        $conn = \App\core\Database::getConnection();

        foreach ($orders as &$order) {

            $stmt = $conn->prepare("
                SELECT 
                    oi.*,
                    p.name,
                    p.image
                FROM order_items oi
                JOIN products p ON p.id = oi.product_id
                WHERE oi.order_id = ?
            ");

            $stmt->execute([$order['id']]);

            $order['items'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        unset($order);
    }
public function index()
{
    // $this->requireRole(['admin']);
    $orders = (new Order())->all();
    $room = new Room ;
    View::make("admin.orders.index", ["orders" => $orders , "rooms"    => $room->all(),]);
}
// ================================================================
    // ADMIN — VIEW SINGLE ORDER
    // GET /orders/view/{id}
// ================================================================
public function view($id)
{
    // $this->requireRole(['admin']);
    $orderId = $id ?? $this->getIdFromUrl();
    $existing = $this->findOrFail($orderId);
    $itemModel = new \App\core\Model('order_items');
    $existing['items'] = $itemModel->where("order_id = $orderId")->get();
    $user = new User();
    $existing['user'] = $user->find($existing['user_id']);
    $existing['room'] = (new Room())->find($existing['room_id']);
    View::make("admin.orders.view", ["order" => $existing]);
}


    // ================================================================
    // ADMIN — UPDATE ORDER
    // POST /orders/update/{id}
    // Edits: room_id, notes, status, items (full control for admin)
    // ================================================================

public function update($id)
{
    // $this->requireRole(['admin']);
    $orderId = $id ?? $this->getIdFromUrl();
    $existing = $this->findOrFail($orderId);

    $roomId = $_POST['room_id'] ?? $existing['room_id'];
    $notes = $_POST['notes'] ?? $existing['notes'];
    $status = $_POST['status'] ?? $existing['status'];

            $validStatuses = ['processing', 'out_for_delivery', 'done', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            $_SESSION['error'] = "invalid: $status";
            header("Location: /orders");
            exit;
        }

 
        $result = (new Order())
            ->where("id = $orderId")
            ->update(
                ['room_id', 'notes', 'status', 'updated_at'],
                [$roomId,   $notes,  $status,  date('Y-m-d H:i:s')]
            );

            if ($result) {
    $_SESSION['success'] = "✅ Order #$orderId has been updated successfully";
} else {
    $_SESSION['error'] = "An error occurred while updating order #$orderId";
}

header("Location: /orders");
exit;
}
    // ================================================================
    // ADMIN — DELETE ORDER
    // POST /orders/delete/{id}
    // Only allowed if status = cancelled
    // ================================================================
 public function delete($id)
 {
    // $this->requireRole(['admin']);
    $orderId = $id ?? $this->getIdFromUrl();
    $existing = $this->findOrFail($orderId);
    if($existing['status']!=='cancelled')
        {
            $_SESSION['error'] = "the order can not be deleted because it isn't cancelled";
            header("Location:/orders");
            exit;
        }
        $result = (new Order())->where("id = $orderId")->delete();
    if ($result) {
    $_SESSION['success'] = "✅ Order #$orderId has been updated successfully";
    } else {
    $_SESSION['error'] = "An error occurred while updating order #$orderId";
    }
header("Location: /orders");
exit;
 }

     // ================================================================
    // ADMIN — TOGGLE ORDER
    // GET /orders/toggle/{id}
    // processing  → cancelled
    // cancelled   → processing
    // ================================================================
 public function toggle($id)
{
    // $this->requireRole(['admin']);
    $orderId = $id ?? $this->getIdFromUrl();
    $existing = $this->findOrFail($orderId);
    $allowed = ['processing', 'cancelled'];
    if (!in_array($existing['status'], $allowed)) {
        $_SESSION['error'] = "The order status cannot be toggled. Current status: {$existing['status']}";
        header("Location: /orders");
        exit;
    }
    $newStatus = $existing['status'] === 'processing' ? 'cancelled' : 'processing';
    $result = (new Order())
        ->where("id = $orderId")
        ->update(
            ['status', 'updated_at'],
            [$newStatus, date('Y-m-d H:i:s')]
        );
    $result
        ? $_SESSION['success'] = "🔄 the order #$orderId became: $newStatus"
        : $_SESSION['error']   = "error within toggle the status of order";
    header("Location: /orders");
    exit;
}
     // ================================================================
    // ADMIN — CONFIRM ORDER
    // POST /orders/confirm/{id}
    // processing → out_for_delivery
    // Admin + Office Boy
    // ================================================================
public function confirmOrder($id)
{
    // $this->requireRole(['admin','office_boy']);
    $orderId = $id ?? $this->getIdFromUrl();
    $existing = $this->findOrFail($orderId);
if ($existing['status'] !== 'processing') {
    $_SESSION['error'] = "The order must be in processing status. Current status: {$existing['status']}";
    $this->redirectBack();
    exit;
}
        $result = (new Order())
            ->where("id = $orderId")
            ->update(
                ['status',           'updated_at'],
                ['out_for_delivery', date('Y-m-d H:i:s')]
            );
 if ($result) {
    $_SESSION['success'] = "✅ Order #$orderId has been updated successfully";
    } else {
    $_SESSION['error'] = "An error occurred while updating order #$orderId";
    }
header("Location: /orders");
exit;
}
    // ================================================================
    // ADMIN — DELIVER ORDER
    // POST /orders/deliver/{id}
    // out_for_delivery → done
    // Admin + Office Boy
    // ================================================================
public function deliverOrder($id)
{
    // $this->requireRole(['admin','office_boy']);
    $orderId = $id ?? $this->getIdFromUrl();
    $existing = $this->findOrFail($orderId);
    if ($existing['status'] !== 'out_for_delivery') {
    $_SESSION['error'] = "The order must be in out_for_delivery status. Current status: {$existing['status']}";
    header("Location: /orders/out_for_delivery");
    exit;
}
   $result = (new Order())
            ->where("id = $orderId")
            ->update(
                ['status',           'updated_at'],
                ['done', date('Y-m-d H:i:s')]
            );
 if ($result) {
    $_SESSION['success'] = "✅ Order #$orderId has been updated successfully";
    } else {
    $_SESSION['error'] = "An error occurred while updating order #$orderId";
    }
header("Location: /orders/out_for_delivery");
exit;
}
    // ================================================================
    // ADMIN — COMPLETE ORDER (force close)
    // POST /orders/complete/{id}
    // any status → done  (Admin only, bypasses normal flow)
    // ================================================================
public function complete($id = null)
{
    // $this->requireRole(['admin']);
    $orderId  = $id ?? $this->getIdFromUrl();
    $existing = $this->findOrFail($orderId);
    if ($existing['status'] === 'done') {
        $_SESSION['error'] = "Order #$orderId is already completed";
        header("Location: /orders");
        exit;
    }
    $result = (new Order())
        ->where("id = $orderId")
        ->update(
            ['status', 'updated_at'],
            ['done',   date('Y-m-d H:i:s')]
        );
    $result
        ? $_SESSION['success'] = "✅ Order #$orderId has been closed manually → done"
        : $_SESSION['error']   = "An error occurred while closing the order";
    header("Location: /orders");
    exit;
}
    // ================================================================
    // ADMIN — CANCEL ORDER
    // POST /orders/cancel/{id}   ← Admin (with {id} in URL)
    // POST /orders/cancel        ← User  (order_id in POST body)
    // processing / out_for_delivery → cancelled
    // ================================================================
    public function cancelOrder($id = null)
{
    $role   = $_SESSION['role'] ?? 'user';
    $userId = $_SESSION['user_id'] ?? null;

    if ($role === 'office_boy') {
        $_SESSION['error'] = "You are not allowed to cancel orders";
        header("Location: /orders");
        exit;
    }

    // Admin gets id from URL, User from POST body
    $orderId = $id ?? $this->getIdFromUrl();
    if (!$orderId) {
        $orderId = $_POST['order_id'] ?? null;
    }

    if (!$orderId) {
        $_SESSION['error'] = "Order not found";
        $this->redirectBack();
        exit;
    }

    $existing = $this->findOrFail((int)$orderId);

    // User can cancel only their own order
    if ($role === 'user' && $existing['user_id'] != $userId) {
        $_SESSION['error'] = "You are not allowed to cancel this order";
        header("Location: /orders/my");
        exit;
    }

    $cancellable = ['processing', 'out_for_delivery'];
    if (!in_array($existing['status'], $cancellable)) {
        $_SESSION['error'] = "The order cannot be cancelled. Current status: {$existing['status']}";
        $this->redirectBack();
        exit;
    }

    $result = (new Order())
        ->where("id = $orderId")
        ->update(
            ['status', 'updated_at'],
            ['cancelled', date('Y-m-d H:i:s')]
        );

    $result
        ? $_SESSION['success'] = "❌ Order #$orderId has been cancelled"
        : $_SESSION['error']   = "An error occurred while cancelling the order";

    $this->redirectBack();
    exit;
}
    // ================================================================
    // ADMIN — DELIVERY QUEUE
    // GET /orders/delivery_queue
    // Shows all out_for_delivery orders
    // Admin + Office Boy
    // ================================================================
    public function deliveryQueue()
    {
        // $this->requireRole(['admin', 'office_boy']);
 
        $order  = new Order();
        $orders = $order->where("status = 'out_for_delivery'")
                        ->orderBy("created_at", "ASC")
                        ->get();
        $this->attachItems($orders);
 
        View::make("admin.orders.delivery_queue", ["orders" => $orders]);
    }
 
    // ================================================================
    // ADMIN — FILTER VIEWS (by status)
    // GET /orders/processing
    // GET /orders/out_for_delivery
    // GET /orders/done
    // GET /orders/cancelled
    // ================================================================
    public function processingOrders()
    {
        // $this->requireRole(['admin', 'office_boy']);
        $orders = (new Order())->where("status = 'processing'")
                               ->orderBy("created_at", "ASC")
                               ->get();
        $this->attachItems($orders);
        View::make("admin.orders.index", [
            "orders"         => $orders,
            "active_filter"  => "processing",
        ]);
    }
 
    public function outForDeliveryOrders()
    {
        // $this->requireRole(['admin', 'office_boy']);
        $orders = (new Order())->where("status = 'out_for_delivery'")
                               ->orderBy("created_at", "ASC")
                               ->get();
        $this->attachItems($orders);
        View::make("admin.orders.index", [
            "orders"        => $orders,
            "active_filter" => "out_for_delivery",
        ]);
    }
 
    public function doneOrders()
    {
        // $this->requireRole(['admin']);
        $orders = (new Order())->where("status = 'done'")
                               ->orderBy("updated_at", "DESC")
                               ->get();
        View::make("admin.orders.index", [
            "orders"        => $orders,
            "active_filter" => "done",
        ]);
    }
 
    public function cancelledOrders()
    {
        // $this->requireRole(['admin']);
        $orders = (new Order())->where("status = 'cancelled'")
                               ->orderBy("updated_at", "DESC")
                               ->get();
        View::make("admin.orders.index", [
            "orders"        => $orders,
            "active_filter" => "cancelled",
        ]);
    }
 
    // ================================================================
    // ADMIN + OFFICE BOY — MANUAL ORDER
    // GET  /orders/manual  → show form
    // POST /orders/manual  → save order
    // ================================================================
public function manualOrder()
{
    // $this->requireRole(['admin', 'office_boy']);

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $room = new Room ;
        var_dump($room->all()) ;
        View::make("admin.orders.manual_order", [
            "products" => (new Product())->where("is_available = 1")->get(),
            "users"    => (new User())->where("is_active = 1")->get(),
            "rooms"    => $room->all(),
        ]);
        return;
    }

    $userId   = $_POST['user_id'] ?? null;
    $roomId   = $_POST['room_id'] ?? null;
    $notes    = $_POST['notes'] ?? '';
    $items    = $_POST['items'] ?? [];
 

    if (!$userId || !$roomId  || empty($items)) {
    $_SESSION['error'] = "Session expired, please login again";
    header("Location: /login");
    exit;
}

    $productModel = new Product();
    $validItems   = [];
    $total        = 0;

    foreach ($items as $item) {
        $productId = $item['product_id'] ?? null;
        $qty       = (int)($item['quantity'] ?? 0);

        if (!$productId || $qty <= 0) continue;

        $product = $productModel->find($productId);

        if (!$product || !$product['is_available']) continue;

        $unitPrice = (float)$product['price'];
        $total += $unitPrice * $qty;

        $validItems[] = [
            'product_id' => (int)$product['id'],
            'quantity'   => $qty,
            'unit_price' => $unitPrice,
        ];
    }

    if (empty($validItems)) {
        $_SESSION['error'] = "No valid products found in this order";
        header("Location: /orders/manual");
        exit;
    }

    $orderId = (new Order())->insert(
        ['user_id',  'room_id', 'notes', 'status', 'total', 'created_at'],
        [$userId, $roomId, $notes, 'out_for_delivery', $total, date('Y-m-d H:i:s')]
    );

    if (!$orderId) {
        $_SESSION['error'] = "An error occurred while creating the order";
        header("Location: /orders/manual");
        exit;
    }

    $orderItemModel = new \App\core\Model('order_items');
    foreach ($validItems as $item) {
        $orderItemModel->insert(
            ['order_id', 'product_id', 'quantity', 'unit_price'],
            [$orderId, $item['product_id'], $item['quantity'], $item['unit_price']]
        );
    }

    $_SESSION['success'] = "Manual order created successfully. Total: {$total} EGP";
    header("Location: /orders");
    exit;
}
        // ================================================================
    // USER — MY ORDERS
    // GET /orders/my
    // ================================================================
    public function myOrders()
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header("Location: /login");
            exit;
        }
        $orders = (new Order())->where("user_id = $userId")
                               ->orderBy("created_at", "DESC")
                               ->get();
        $this->attachItems($orders);
       
        View::make("user.orders.my_orders", ["orders" => $orders]);
    }
    // ================================================================
    // USER — VIEW SINGLE ORDER
    // GET /orders/my/{id}
    // ================================================================
    public function showMyOrder($id = null)
    {
        $userId  = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header("Location: /login");
            exit;
        }
        $orderId  = $id ?? $this->getIdFromUrl();
        $existing = $this->findOrFail((int)$orderId, "/orders/my");
        if ($existing['user_id'] != $userId) {
            $_SESSION['error'] = "not allow to show this order";
            header("Location: /orders/my");
            exit;
        }
        $itemModel = new \App\core\Model('order_items');
        $existing['items'] = $itemModel->where("order_id = $orderId")->get();
        $existing['room'] = (new Room())->find($existing['room_id']);
        View::make("user.orders.show", ["order" => $existing]);
    }

    public function officeBoyIndex()
{
    $orders = (new Order())->where("status = 'processing'")
                           ->orderBy("created_at", "ASC")
                           ->get();
    $this->attachItems($orders);
    View::make("office_boy.index", ["orders" => $orders]);
}
}


