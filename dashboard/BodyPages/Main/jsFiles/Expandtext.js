let expand = 1;
function ExpandNavTabBarText() {
    const NavTabBarTitle = document.getElementsByClassName("NavTabBarTitle");
    const NavTabBarTextBtn = document.getElementsByClassName("NavTabBarTextBtn");
    const NavTabBarTextBtnIcon = document.getElementsByClassName("NavTabBarTextBtnIcon");
    if (expand) {
        for (let icons = 0; icons < NavTabBarTitle.length; icons++) {

            NavTabBarTitle[icons].classList.add("NavTabBarTitleShow");
        }
        NavTabBarTextBtn[0].classList.add("ExpandedNavTabBarTextBtn");
        NavTabBarTextBtnIcon[0].classList.add("RotatedNavTabBarTextBtnIcon");
        
        expand = 0;
    } else {
        for (let icons = 0; icons < NavTabBarTitle.length; icons++) {
            NavTabBarTitle[icons].classList.remove("NavTabBarTitleShow");
        }
        NavTabBarTextBtn[0].classList.remove("ExpandedNavTabBarTextBtn");
        NavTabBarTextBtnIcon[0].classList.remove("RotatedNavTabBarTextBtnIcon");
        expand = 1;
    }
    console.log(expand)
};


