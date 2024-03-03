<div class="Body">
    <!-- Register form -->
    <form class="Lform" id="registerForm" method="POST" action="./checkLoginServer.php/?register=true" onload="EvalUserInpt()">
        <p>Register</p>
        <input class="UserToFilter" type="text" name="username" placeholder="username" id="Lusername" required>
        <input class="PassToFilter" type="password" name="password" placeholder="Password" id="Lpassword" required>
        <span class="LogToUser UserLog"></span>
        <button type="Button" class="btnA" onclick="validateAtServer('Lusername','Lpassowrd')">Submit</button>
    </form>
    <!-- goto login -->
    <div id="noPass">
        <p>Already have an account?
            <a href="./index.php?redirect=login">Login</a>
        </p>
</div>

<link rel="stylesheet" href="dashboard/BodyPages/LoginAndRegister/Styles/signPageStyle.css" media="print" onload="this.media='all'">
<link rel="stylesheet" href="dashboard/BodyPages/LoginAndRegister/Styles/EvalUserInpt.css" media="print" onload="this.media='all'">

<!-- Js files -->
<script src="dashboard/BodyPages/LoginAndRegister/jsFiles/validateAtServer.js" async="false" crossorigin="anonymous"></script>
<script src="dashboard/BodyPages/LoginAndRegister/jsFiles/EvalUserInpt.js" async="false" crossorigin="anonymous"></script>