<?php if ((isset($_GET["register"]) && !empty($_GET['register'])) === true):?>
<link rel="stylesheet" href="../CssFiles/LoginStyle.css">
<p>Register</p>
<form id="Lform" method="POST" action="./checkLoginServer.php/?register=true" id="Login">
    <input type="text" name="username" placeholder="username" id="Lusername" required>
    <input type="password" name="password" placeholder="Password" id="Lpassword" required>
    <button type="Button" onclick="validateAndSubmit()">Submit</button>
</form>
<div id="noPass">
    <p>Already have an account?</p>
    <a href="../dashboard/index.php/">Login</a>
</div>
<script src="../js/loginValidator.js" crossorigin="Local"></script>
<?php else: ?>
    <link rel="stylesheet" href="../CssFiles/LoginStyle.css">
    <p>login</p>
    <form id="Lform" method="POST" action="../Body/checkLoginServer.php" id="Login">
        <input type="text" name="username" placeholder="username" id="Lusername" required>
        <input type="password" name="password" placeholder="Password" id="Lpassword" required>
        <button type="Button" onclick="validateAndSubmit()">Submit</button>
    </form>
    <div id="noPass">
        <p>
            <b>
                Dont have an account or cant access?
            </b>
            <a href="../dashboard/index.php/?register=true">Then register</a>
        </p>
    </div>
    <script src="../js/loginValidator.js" crossorigin="Local"></script>
<?php endif ?>