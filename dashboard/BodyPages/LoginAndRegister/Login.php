<div class="Body">
    <form class="Lform" id="loginForm" method="POST" action="/dashboard/">
        <h3>Login</h3>
        <input class="UserToFilter" type="text" name="username" placeholder="username" id="Lusername" required>
        <input class="PassToFilter" type="password" name="password" placeholder="Password" id="Lpassword" required>
        <span class="LogToUser UserLog"></span>
        <label>
            <input type="checkbox" name="remeberPass" id="rememberPass" placeholder="Stay logged in">
            <span>Stay logged in?</span>
        </label>
        <button class="btnA" type="Button" onclick="validateAtServer()">Submit</button>
    </form>
    <div class="UserF">
        <label>Don't have an account or cant access?
        </label>
        <button class="btnA openBtn" onclick=" EvalUserInpt('.adminCheck','.adminPCheck','.chckadmin');" type="button">then
            register or change password</button>
    </div>
    <div id="adminpopup" class="nodisplay popUp">
        <form class="Lform auth" id="adminform" method="POST" action="./index.php?redirect=register">
            <span>Admin Authentication</span>
            <input type='text' class="adminCheck" name='Adminusername' Placeholder='Admin username' id='adminUsername'
                required>
            <input type='password' class="adminPCheck" name='Adminpassword' placeholder='Admin password'
                id="adminPassword" require>
            <p class="chckadmin UserLog"></p>
            <button type="Button" class="btnA"
                onclick="validateAtServer('adminUsername','adminPassword')">Validate</button>
        </form>
        <span type="button" onclick="EvalUserInpt()" class="exitbtn btnB closeBtn">
            <i class="fab fa-mixer"></i>
        </span>
    </div>
</div>

<link rel="stylesheet" href="dashboard/BodyPages/LoginAndRegister/Styles/popUp.css" media="print" onload="this.media='all'">
<link rel="stylesheet" href="dashboard/BodyPages/LoginAndRegister/Styles/signPageStyle.css" media="print" onload="this.media='all'">
<link rel="stylesheet" href="dashboard/BodyPages/LoginAndRegister/Styles/EvalUserInpt.css" media="print" onload="this.media='all'">

<!-- Js files -->
<script src="dashboard/BodyPages/LoginAndRegister/jsFiles/EvalUserInpt.js" async="true" crossorigin="anonymous"></script>
<script src="dashboard/BodyPages/LoginAndRegister/jsFiles/validateAtServer.js" async="true" crossorigin="anonymous"></script>
<script src="dashboard/BodyPages/LoginAndRegister/jsFiles/popUp.js" async="true" crossorigin="anonymous"></script>