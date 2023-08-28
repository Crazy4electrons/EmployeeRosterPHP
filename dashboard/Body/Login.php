<link id="logonStyle" rel="stylesheet" href="../CssFiles/LoginStyle.css">
<?php if ((isset($_GET['login']) && !empty($_GET['login']))) : ?>
    <?php if (!$_GET['login']) : ?>
        <p>Register</p>
        <form  class="Lform" id="registerForm" method="POST" action="./checkLoginServer.php/?register=true">
            <input type="text" name="username" placeholder="username" id="Lusername" required>
            <input type="password" name="password" placeholder="Password" id="Lpassword" required>
            <button type="Button" onclick="validateAtServer('RegisterForm','Lusername','Lpassowrd',false)">Submit</button>
        </form>
        <div id="noPass">
            <p>Already have an account?
                <a href="../dashboard/index.php/">Login</a>
            </p>
        </div>
    <?php else : ?>
        <form class="Lform" id="loginForm" method="POST" action="./checkLoginServer.php">
            <h3>Login</h3>
            <input type="text" name="username" placeholder="username" id="Lusername" required>
            <input type="password" name="password" placeholder="Password" id="Lpassword" required>
            <span id="notice"></span>
            <label>
                <input type="checkbox" name="remeberPass" id="rememberPass" placeholder="Stay logged in">
                <span>Stay logged in?</span>
            </label>
            <button class="btnA" type="Button" onclick="validateAtServer('loginForm','Lusername','Lpassowrd',false)">Submit</button>
        </form>
        <div>
            <label>Don't have an account or cant access?
                <button id="openbtn" class="btnA" type="button">then register or change password</button>
            </label>
        </div>
        <div id="adminpopup" class=" nodisplay">
            <form class="Lform auth" id="adminform" action="/dashboard/index.php?redirect=login">
                <span>Admin Authentication</span>
                <input type='text' name='Adminusername' Placeholder='Admin username' id='adminusername' required>
                <input type='password' name='Adminpassword' placeholder='Admin password' id="adminpassword" require>
                <button type="Button" onclick="validateAtServe('adminform','adminusername','adminpassword',true)">Validate</button>
            </form>
            <button id="closebtn" type="button" class="btnB">
                <i class="fab fa-mixer"></i>
            </button>
        </div>
    <?php endif ?>
<?php endif ?>
<script src="../js/all.js" crossorigin="local" async="true"></script>
<script src=" ../js/loginValidator.js" async="true" crossorigin="Local"></script>
<script src=" ../js/listeners.js" async="false" crossorigin="Local"></script>
