$(function () {
    //  Current Year in Footer
    const currentYear = new Date().getFullYear();
    $("#znjCopyRight").html(
        `${currentYear} <span class="adress-last-link-highlight">ZNJ</span> - All Rights Reserved.`
    );

    //  Remember Password Checkbox accent color
    $("#rememberPassword").on("change", function () {
        if ($(this).is(":checked")) {
            $(this).css("accent-color", "yellow");
        } else {
            $(this).css("accent-color", "");
        }
    });

    /dashboard right side content toggle/;
    $(document).on("click", "#sidebarToggle", function () {
        $("#dashboardSidebar").toggleClass("sidebar-toggle");
        /Side-nav-overlay/;
        $("#sideNavOverlay").removeClass("d-none");
        $("#dashboardSidebarRightContent").toggleClass("right-toggled");
    });

    $(".my-nav-link-header").on("click", function () {
        $(".my-nav-link-header").css("color", "");
        $(this).css("color", "#e5c530");
    });
    $(".dash-create-join-anchor-btn").on("click", function () {
        $(".dash-create-join-anchor-btn").css("background-color", "");
        $(this).css("background-color", "#e5c530");
    });

    function sideBarToggleSwitch() {
        $(document).on("click", function (event) {
            if (
                $(window).width() <= 991 &&
                !$(event.target).closest("#dashboardSidebar, #sidebarToggle")
                    .length
            ) {
                $("#dashboardSidebar").addClass("sidebar-toggle");
                /Side-nav-overlay/;
                $("#sideNavOverlay").addClass("d-none");
            }
        });
    }
    /*dashboard right side content and sidebar toggle switching*/
    sideBarToggleSwitch();

    if($(window).width()<991) {
        $("#dashboardSidebar").addClass("sidebar-toggle");
    }

    $(window).resize(function () {
        /*dashboard right side content and sidebar toggle switching*/
        sideBarToggleSwitch();
        if($(window).width()<991) {
            $("#dashboardSidebar").addClass("sidebar-toggle");
        } else {
            $("#dashboardSidebar").removeClass("sidebar-toggle");
        }
    });

    $(".toggle-password").on("click", function () {
        let input = $("#exampleInputPassword1"); 
        let $this = $(this); 

        if (input.attr("type") === "password") {
            input.attr("type", "text");
            $this.removeClass("fa-eye-slash").addClass("bi-eye-slash");
        } else {
            input.attr("type", "password");
            $this.removeClass("bi-eye-slash").addClass("fa-eye-slash");
        }
    });
});
