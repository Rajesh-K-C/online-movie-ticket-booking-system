<section class="forms">
    <div>
        <button type="button" id="login" class="selected">Login</button>
        <button type="button" id="signUp">Register</button>
    </div>
    <div id="message"></div>
    <?php loginForm("#"); ?>
    <form class="signup-form display-none" action="requests/signup.php" method="post">
        <div>
            <div class="input-control">
                <label for="fName">First Name </label>
                <input type="text" name="fName" id="fName" placeholder="Enter your first name">
                <span class="err" id="fnErr"></span>
            </div>
            <div class="input-control">
                <label for="lName">Last Name </label>
                <input type="text" name="lName" id="lName" placeholder="Enter your last name">
                <span class="err" id="lnErr"></span>
            </div>
        </div>
        <div>
            <div class="input-control">
                <label for="email">Email </label>
                <input type="email" name="email" id="email" autocomplete="email" placeholder="Enter your email">
                <span class="err" id="emailErr"></span>
            </div>
            <div class="input-control">
                <label for="phone">Phone </label>
                <input type="tel" name="phone" id="phone" autocomplete="phone"
                    placeholder="Enter your phone number">
                <span class="err" id="phoneErr"></span>
            </div>
        </div>
        <div>
            <div class="input-control">
                <label for="password">Password </label>
                <input type="password" name="password" id="password" placeholder="Enter your password">
                <span class="err" id="pErr"></span>
            </div>
            <div class="input-control">
                <label for="cPassword">Confirm Password </label>
                <input type="password" name="cPassword" id="cPassword" placeholder="Confirm your password">
                <span class="err" id="cpErr"></span>
            </div>
        </div>
        <div class="input-control">
            <div>
                <input type="submit" name="signup" value="Register">
            </div>
        </div>
    </form>
</section>