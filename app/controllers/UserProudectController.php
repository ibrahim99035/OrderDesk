<?php
namespace App\controllers ;

use App\core\Database;
use App\core\View;
use App\models\Category;
use App\models\Order;
use App\models\OrderItem;
use App\models\Product;
use App\models\Room;
use App\models\User;
use App\request\AddOrders;
use Exception;

class UserProudectController{
    public function index(){
        $pro = new Product() ;
        $catgory = new Category ;
        $rooms = new Room ;
        
        if(isset($_GET["id"]) ){
            $id = $_GET['id'] ;
            $proudects = $pro->where("category_id=$id")->get() ;
        }else{
            $proudects = $pro->all() ;
        }
        $catgroies =  $catgory->all() ;

        if(!$proudects) $proudects = [];
        if(!$catgroies) $catgroies = [];
        View::make("user.product" , ["proudects" => $proudects,"catgroies" => $catgroies , "current" => 'proudects' , "rooms" => $rooms->all() ]);
    }

    public function checkout(){
        try{
            $request = new AddOrders();

            if (!$request->validate()) {
                return "validation error";
            }
            $cart = json_decode($_POST["cart"], true);
        

            $order = new Order;
            if(!isset($_SESSION["user_id"])){
                header("Location: /login");
                exit;
            }
            $user = (new User)->find($_SESSION["user_id"]) ;
        
            if(isset($_POST["room_id"]) &&  !empty($_POST["room_id"]) ){
                $room_id = $_POST["room_id"];
            }else{
                $room_id = $user["room_id"] ;
                var_dump($user) ;
            }
            $order->insert(["user_id" , "room_id" , "total"  ],
            [$_SESSION["user_id"] , $room_id , 0]);
            $conn = Database::getConnection();

            $order_id = $conn->lastInsertId();

            $total = 0;

            $itemModel = new OrderItem();

            foreach ($cart as $i) {

                $itemModel->insert(["order_id" , "product_id" , "quantity", "unit_price" ]
                ,[$order_id, $i["id"] ,$i["qty"] ,$i["price"] ]);

                $total += $i["price"] * $i["qty"];
            }

            $order->where("id=$order_id")->update( [ "total"],[ $total ]);
            $_SESSION["success"] = "order added sucsuful";
            header("Location: /product");

            

        }catch(Exception $e){
            $_SESSION["error"] = "faild to added order";
            header("Location: /product");
        }
    
    }
}
