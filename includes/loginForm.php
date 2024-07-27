<?php
function loginForm($action): void
{
    ?>
    <form class="login-form" action="<?= $action; ?>" method="post">
        <div class="input-control">
            <label for="loginEmail">Email </label>
            <input type="email" name="email" id="loginEmail" autocomplete="email" placeholder="Enter your email">
        </div>
        <div class="input-control">
            <label for="loginPassword">Password </label>
            <input type="password" name="password" id="loginPassword" placeholder="Enter your password">
        </div>
        <div class="input-control">
            <div>
                <input type="submit" name="login" value="Login">
            </div>
        </div>
    </form>
    <?php
}
?>