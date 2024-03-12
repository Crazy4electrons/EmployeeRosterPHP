    <div class=" NavtabPage">
        <div class="NavTabBars">
            <button title="Dashboard" type="button" class="NavTabBar" onclick="NavTabPage(0)">
                <i class="fa fa-dashboard" aria-hidden="true"></i>
                <span class="NavTabBarTitle">Dashboard</span>
            </button>
            <button title="Calendar" type="button" class="NavTabBar" onclick="NavTabPage(1)">
                <i class="fa-regular fa-calendar"></i>
                <span class="NavTabBarTitle">Calender</span>
            </button>
            <button title="Dashd" type="button" class="NavTabBar" onclick="NavTabPage(2)">
                <i class="fa-regular fa-calendar"></i>
                <span class="NavTabBarTitle">Dashboard</span>
            </button>
            <button id="NavTabBarTextBtn" title="NavTabBarTextBtn" type="button" class="NavTabBarTextBtn" onclick="ExpandNavTabBarText()">
                <i class="fa-solid fa-chevron-right NavTabBarTextBtnIcon"></i>
            </button>
        </div>
        <div class="NavTabViews">
            <div id="Dashboard" class="NavTabView">
                <?php
                include "./dashboard/BodyPages/Main/Modules/Notes/Notes.php";
                include "./dashboard/BodyPages/Main/Modules/Schedule/Schedule.php";
                include "./dashboard/BodyPages/Main/Modules/Charts/Charts.php";
                ?>

                <div class="">

                    dash
                </div>
                <div class="">

                    dash
                </div>
            </div>
            <div id="Calendar" class="NavTabView">
                <div class="">
                    calendar
                </div>
                <div class="">
                    calendar
                </div>
                <div class="">
                    calendar
                </div>
                <div class="">
                    calendar
                </div>
            </div>
            <div id="Cale" class="NavTabView">
                <div class="">
                    calend666ar
                </div>
                <div class="">
                    calendar
                </div>
                <div class="">
                    calendar
                </div>
                <div class="">
                    calendar
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="dashboard/BodyPages/Main/Styles/NavTabPage.css" media="print" onload="this.media='all'">
    <script src="dashboard/BodyPages/Main/jsFiles/Expandtext.js" async="true" crossorigin="anonymous"></script>
    <script src="dashboard/BodyPages/Main/jsFiles/NavTabPage.js" async="true" crossorigin="anonymous"></script>