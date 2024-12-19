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
                foreach ($cart as $item) {
                    echo $item['departure'].' - '.$item['arrival'].' - '.$item['ship'].'<br>';
                }
            }
        } else {
            echo '<p>Your cart is empty</p>';
        }
        echo "<a href='include/deleteCart.php'>Delete cart</a>";
        ?>
    </div>
</div>