<link rel="stylesheet" href="../CssFiles/LoginStyle.css">
<?php if ((isset($_GET['login']) && !empty($_GET['login']))) : ?>
    <?php if (!$_GET['login']) : ?>
        <p>Register</p>
        <form id="Lform" method="POST" action="./checkLoginServer.php/?register=true" id="Login">
            <input type="text" name="username" placeholder="username" id="Lusername" required>
            <input type="password" name="password" placeholder="Password" id="Lpassword" required>
            <button type="Button" onclick="validateAndSubmit()">Submit</button>
        </form>
        <div id="noPass">
            <p>Already have an account?
                <a href="../dashboard/index.php/">Login</a>
            </p>
        </div>
    <?php else : ?>
        <p>login</p>
        <form id="Lform" method="POST" action="../Body/checkLoginServer.php" id="Login">
            <input type="text" name="username" placeholder="username" id="Lusername" required>
            <input type="password" name="password" placeholder="Password" id="Lpassword" required>
            <label>
                <input style="display:inline" type="checkbox" name="remeberPass" id="rememberPass" placeholder="Stay logged in">
                <span>Stay logged in?</span>
            </label>
            <button type="Button" onclick="validateAndSubmit()">Submit</button>
        </form>
        <div id="noPass">
            <p>
                <b>
                    Dont have an account or cant access?
                </b>
                <button type="button" onclick="checkwithadmin()">then register or change password</button>
            </p>
        </div>
    <?php endif ?>
<?php endif ?>
<script src=" ../js/loginValidator.js" async="true" crossorigin="Local"></script>