<?php
global $departure;
global $arrival;
?>

<script>
    function showCart() {
        var cart = document.querySelector('.cartDetail');
        if (cart.style.display === 'block') {
            cart.style.display = 'none';
        } else {
            cart.style.display = 'block';
        }
    }
</script>

<div class="cart" onclick="showCart()">
    <img src="images/cart.png" width="40">

    <div class="cartDetail">
        <?php
        if (isset($_COOKIE['cart'])) {
            $cart = unserialize($_COOKIE['cart']);
            if (count($cart) == 0) {
                echo '<p>Your cart is empty</p>';
            }
            else {
                echo "You have ".count($cart)." items in your cart<br>";
                echo "<a href='checkout.php'>Checkout</a><br>";
                foreach ($cart as $item) {
                    ?>
                    <div class="cartItem">
                        <div class="cartItemDeparture"><?php
                            echo "<b>From</b> ".$item->getDeparture();
                            ?></div>
                        <div class="cartItemArrival"><?php
                            echo "<b>To</b> ".$item->getArrival();
                            ?></div>
                        <div class="cartItemShip"><?php
                            // get ship name from database
                            $stmt = $cnx->prepare("SELECT name FROM ships WHERE id = :id");
                            $shipCart = $item->getShip();
                            $stmt->bindParam(':id', $shipCart, PDO::PARAM_INT);
                            $stmt->execute();
                            $ship = $stmt->fetch();
                            echo "<b>With</b> ".$ship['name'];
                            ?></div>
                        <div class="cartItemQuantity"><?php
                            // input type number changing quantity
                            echo "<b>Quantity</b> ".$item->getQuantity();
                            ?>
                        </div>
                        <div class="cartItemDelete">
                            <a href="include/deleteCartItem.php?id=<?php echo $item->getId(); ?>">Delete</a>
                        </div>
                    </div>
                    <?php
                }
                echo "<a href='include/deleteCart.php'>Empty cart</a>";
            }
        } else {
            echo '<p>Your cart is empty</p>';
        }
        ?>
    </div>
</div>