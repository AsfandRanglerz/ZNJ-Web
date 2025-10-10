$(function () {
    /* page loader */
    $(window).on("load", function () {
    $("#pgLoader").fadeOut("slow");
});

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

    if ($(window).width() < 991) {
        $("#dashboardSidebar").addClass("sidebar-toggle");
    }

    $(window).resize(function () {
        /*dashboard right side content and sidebar toggle switching*/
        sideBarToggleSwitch();
        if ($(window).width() < 991) {
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
 function toggleTicketPrice() {
        let type = $('#joining_type').val();
        let priceField = $('#ticket_price');

if (type === 'Free') {
    priceField.val(0); // set default 0
    priceField.prop('readonly', true); // readonly instead of disabled
} else if (type === 'Paid') {
    priceField.prop('readonly', false); // allow editing
    if (priceField.val() == 0 || priceField.val() === '') {
        priceField.val(''); // clear when switching to paid
    }
}
    }

    // Run on load (for old value or initial state)
    toggleTicketPrice();

    // Run when dropdown changes
    $('#joining_type').on('change', toggleTicketPrice);
    document.addEventListener("DOMContentLoaded", function() {
    flatpickr("input[name='date']", {
        dateFormat: "Y-m-d",
        minDate: "today",
        allowInput: true,
    });
});
});
