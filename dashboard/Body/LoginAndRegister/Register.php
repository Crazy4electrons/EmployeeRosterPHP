

<link id="logonStyle" rel="stylesheet" href="dashboard/CssFiles/LoginStyle.css">
<form class="Lform" id="registerForm" method="POST" action="./checkLoginServer.php/?register=true">
    <p>Register</p> <input class="UserToFilter" type="text" name="username" placeholder="username" id="Lusername" required>
    <input class="PassToFilter" type="password" name="password" placeholder="Password" id="Lpassword" required>
    <span class="LogToUser UserLog"></span>
    <button type="Button" class="btnA" onclick="validateAtServer('Lusername','Lpassowrd')">Submit</button>
</form>
<div id="noPass">
    <p>Already have an account?
        <a href="./index.php?redirect=login">Login</a>
    </p>
</div>
<script src="dashboard/js/listeners.js" async="false" crossorigin="anonymous"></script>
<script src="dashboard/js/loginValidator.js" async="false" crossorigin="anonymous"></script>
<script src="dashboard/js/main.js" async="false" crossorigin="anonymous"></script>