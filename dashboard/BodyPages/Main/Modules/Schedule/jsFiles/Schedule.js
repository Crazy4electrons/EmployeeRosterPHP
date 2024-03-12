let lastScheduleTabBtn = 0;
function ScheduleTabPage(ScheduleTabBtn = 0) {
    let ScheduleTabBars = document.getElementsByClassName("ScheduleTabBar");
    let ScheduleTabViews = document.getElementsByClassName("ScheduleTabView");
    let ScheduleTabBarTitle = document.getElementsByClassName("ScheduleTabBarTitle");
    if (lastScheduleTabBtn != ScheduleTabBtn) {
        ScheduleTabBars[lastScheduleTabBtn].classList.remove("ActiveScheduleTabBar");
        ScheduleTabViews[lastScheduleTabBtn].classList.remove("ActiveScheduleTabView");
        ScheduleTabBarTitle[lastScheduleTabBtn].classList.remove("ActiveScheduleTabBarTitle");
        lastScheduleTabBtn = ScheduleTabBtn;
    };
    ScheduleTabBars[ScheduleTabBtn].classList.add("ActiveScheduleTabBar");
    ScheduleTabViews[ScheduleTabBtn].classList.add("ActiveScheduleTabView");
    ScheduleTabBarTitle[ScheduleTabBtn].classList.add("ActiveScheduleTabBarTitle");
};
window.addEventListener("load", ScheduleTabPage());