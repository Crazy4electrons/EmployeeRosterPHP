let lastNavTabBtn = 0;
function NavTabPage(TabName = null, NavTabBtn = 0) {
    let TabItems = document.getElementsByClassName("Tab-item");
    let NavTab = document.getElementsByClassName("NavTab");
    for (let i = 0; i < TabItems.length; i++) {
        // TabItems[i].style.display = "none"
        TabItems[i].classList.add("HideTab");

    };
    if (TabName == null) {
        TabItems[0].classList.remove("HideTab");
        TabItems[0].classList.add("ShowTab");
        NavTab[NavTabBtn].classList.add("activeTab");
        lastNavTabBtn = NavTabBtn;
    } else {
        document.getElementById(TabName).classList.remove("HideTab");
        document.getElementById(TabName).classList.add("ShowTab");
        NavTab[NavTabBtn].classList.add("activeTab");
        if (lastNavTabBtn != NavTabBtn) {
            TabItems[lastNavTabBtn].classList.remove("ShowTab");
            TabItems[lastNavTabBtn].classList.add("HideTab");
            NavTab[lastNavTabBtn].classList.remove("activeTab");
            lastNavTabBtn = NavTabBtn;
        };
    };
  
    };
window.addEventListener("load", NavTabPage());