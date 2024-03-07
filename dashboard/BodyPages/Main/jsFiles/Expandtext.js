let expand = 0;
function BarText(epand = 0) {
    let NavTabBarTitle = document.getElementsByClassName("NavTabBarTitle");
    let NavTabBarTextBtn = document.getElementsByClassName("NavTabBarTextBtn");
    if (expand = 0) {
        // for (let i = 0; i < NavTabBarTitle.length; i++) {
        NavTabBarTitle[0].style.display = "block" ;
        //     };
        expand = 1;
        //     NavTabBarTextBtn[0].classList.add("ExpandedNavTabBarTextBtn");
    } else {
        //     for (let i = 0; i < NavTabBarTitle.length; i++) {
        NavTabBarTitle[0].style.display = "none";
        //     };
        //     NavTabBarTextBtn[0].classList.remove("ExpandedNavTabBarTextBtn");
        expand = 0;
    };
};
window.addEventListener("load", BarText);