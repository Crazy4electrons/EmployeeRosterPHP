let lastNavTabBtn = 0;
function NavTabPage(NavTabBtn = 0) {
    let NavTabBars = document.getElementsByClassName("NavTabBar");
    let NavTabViews = document.getElementsByClassName("NavTabView");
    if (lastNavTabBtn != NavTabBtn) {
        NavTabBars[lastNavTabBtn].classList.remove("ActiveNavTabBar");
        NavTabViews[lastNavTabBtn].classList.remove("ActiveNavTabView");
        lastNavTabBtn = NavTabBtn;
    };
    NavTabBars[NavTabBtn].classList.add("ActiveNavTabBar");
    NavTabViews[NavTabBtn].classList.add("ActiveNavTabView");
};
window.addEventListener("load", NavTabPage());
