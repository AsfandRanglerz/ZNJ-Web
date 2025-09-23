$(function () {
    //  Current Year in Footer
    const currentYear = new Date().getFullYear();
    $('#znjCopyRight').html(`${currentYear} <span class="adress-last-link-highlight">ZNJ</span> - All Rights Reserved.`);

    //  Remember Password Checkbox accent color
    $("#rememberPassword").on("change", function () {
        if ($(this).is(":checked")) {
            $(this).css("accent-color", "yellow");
        } else {
            $(this).css("accent-color", "");
        }
    });

    // Sidebar Toggle
    const toggleBtn = document.getElementById("sidebarToggle");
    if (toggleBtn) {
        toggleBtn.addEventListener("click", function () {
            document.body.classList.toggle("collapsed");
        });
    }
    $('.my-nav-link-header').on("click", function() {
     $('.my-nav-link-header').css("color", "");
      $(this).css("color", "#e5c530");
    });
        $('.dash-create-join-anchor-btn').on("click", function() {
     $('.dash-create-join-anchor-btn').css("background-color", "");
      $(this).css("background-color", "#e5c530");
    });

   document.querySelector('.toggle-password').addEventListener('click', function () {
  const passwordInput = document.getElementById('exampleInputPassword1');
  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    this.classList.remove("bi-eye");
    this.classList.add("bi-eye-slash");
  } else {
    passwordInput.type = "password";
    this.classList.remove("bi-eye-slash");
    this.classList.add("bi-eye");
  }
});



});



