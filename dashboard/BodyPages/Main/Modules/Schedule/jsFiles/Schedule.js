let lastScheduleTabBtn = 0;
function ScheduleTabPage(ScheduleTabBtn = 0) {
    let ScheduleTabBars = document.getElementsByClassName("ScheduleTabBar");
    let ScheduleTabViews = document.getElementsByClassName("ScheduleTabView");
    if (lastScheduleTabBtn != ScheduleTabBtn) {
        ScheduleTabBars[lastScheduleTabBtn].classList.remove("ActiveScheduleTabBar");
        ScheduleTabViews[lastScheduleTabBtn].classList.remove("ActiveScheduleTabView");
        lastScheduleTabBtn = ScheduleTabBtn;
    };
    ScheduleTabBars[ScheduleTabBtn].classList.add("ActiveScheduleTabBar");
    ScheduleTabViews[ScheduleTabBtn].classList.add("ActiveScheduleTabView");
};
window.addEventListener("load", ScheduleTabPage());