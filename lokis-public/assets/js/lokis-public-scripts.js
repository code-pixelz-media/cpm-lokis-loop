//sending ajax post to wordpress to check answer from given data coming from games post page (single-games.php)
jQuery(document).ready(function ($) {
  $("#lokis-submit-btn").click(function (event) {
    event.preventDefault();

    //Pulls values from the form
    var answer = $("#lokis-answer").val();
    var post_id = $("#loki-post-id").val();
    var session_id = $("#loki-session-id").val();
    var current_user_id = $("#loki-player-id").val();

    $.ajax({
      type: "POST",
      url: gamesajax.ajaxurl,
      data: {
        action: "lokis_check_answer",
        post_id: post_id,
        answer: answer,
        session_id: session_id,
        current_user_id: current_user_id,
      },
      success: function (response) {
        if (response.message == "correct") {
          $("#lokis-feedback").html(
            '<div class="lokis-loop-correct">Answer is correct</div>'
          );
          document.cookie =
            "lokis_passed=" +
            "passed" +
            "; path=/; expires=" +
            response.expiry_time +
            ";";
          setTimeout(function () {
            window.location.href = response.redirect;
          }, 1000);
        } else {
          $("#lokis-feedback").html(
            '<div class="lokis-loop-incorrect">Incorrect answer</div>'
          );

          // Fade out the error message after one second
          setTimeout(function () {
            $(".lokis-loop-incorrect").fadeOut(2000);
          }, 1000);
        }
      },
    });
  });
});

//sending ajax post to wordpress to check answer from given data coming from games post page (single-games.php)
jQuery(document).ready(function ($) {
  $("#lokis-offline-submit-btn").click(function (event) {
    event.preventDefault();

    //Pulls values from the form
    var answer = $("#lokis-answer").val();
    var post_id = $("#loki-post-id").val();

    $.ajax({
      type: "POST",
      url: gamesajax.ajaxurl,
      data: {
        action: "lokis_offline_check_answer",
        post_id: post_id,
        answer: answer,
      },
      success: function (response) {
        if (response.message == "correct") {
          $("#lokis-feedback").html(
            '<div class="lokis-loop-correct">Answer is correct</div>'
          );
          setTimeout(function () {
            window.location.href = response.redirect;
          }, 1000);
        } else {
          $("#lokis-feedback").html(
            '<div class="lokis-loop-incorrect">Incorrect answer</div>'
          );

          // Fade out the error message after one second
          setTimeout(function () {
            $(".lokis-loop-incorrect").fadeOut(2000);
          }, 1000);
        }
      },
    });
  });
});

//Form validation to check input and email format from registration form shortcode (cpm-lokis-loop-custom-shortcodes.php)
jQuery(document).ready(function ($) {
  $("#lokis-registration-button").click(function (event) {
    //Pull values from form
    var name = $("#loki-name").val();
    var email = $("#loki-email").val();
    var organization_name = $("#loki-organization").val();
    var organization_type = $(
      "input[name=loki_organization_type]:checked"
    ).val();
    var country_name = $("#loki-country").val();
    var zipcode = $("#loki-zipcode").val();
    var nonce = $("#loki_registration_nonce").val();
    var errors = [];

    //Clearing array if it has any values
    if (errors.length > 0) {
      errors = [];
    }

    if (name == "") {
      errors.push("Name is required.");
    }

    // Validate email format
    var email_pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email == "") {
      errors.push("Email is required.");
    } else if (!email_pattern.test(email)) {
      errors.push("Please enter a valid email address.");
    }

    if (organization_name == "") {
      errors.push("Organization name is required.");
    }

    if (organization_type == undefined) {
      errors.push("Please select your organization type.");
    }

    if (country_name == "") {
      errors.push("Country is required.");
    }

    if (zipcode == "") {
      errors.push("Zipcode is required.");
    }

    if (errors.length > 0) {
      //Clear div
      $("#lokis-error-message").html("");
      $("#lokis-feedback").html("");
      // Display error messages
      var error_message = "Please correct the following errors:<br><ul>";
      for (var i = 0; i < errors.length; i++) {
        error_message += "<li>" + errors[i] + "</li>";
      }
      error_message += "</ul>";

      $("#lokis-error-message").addClass("lokis-loop-error");
      $("#lokis-error-message").css("height", "auto");
      $("#lokis-error-message").html(error_message);
      $("#lokis-error-message").show();
      // Fade out the error message after one second
      setTimeout(function () {
        $("#lokis-error-message").fadeOut(2000);
      }, 1000);

      // Scroll to the top of the page
      $("html, body").animate({ scrollTop: 0 }, "fast");
      return false;
    } else {
      event.preventDefault();

      $.ajax({
        type: "POST",
        url: gamesajax.ajaxurl,
        data: {
          action: "loki_user_registration",
          name: name,
          email: email,
          organization_name: organization_name,
          organization_type: organization_type,
          country_name: country_name,
          zipcode: zipcode,
          nonce: nonce,
        },

        success: function (response) {
          if (response.status === "success") {
            //Clear input fields
            $("form :input").val("");
            var radio1 = document.getElementsByName("loki_organization_type");

            // Loop through the radio buttons and unset the checked property
            for (var i = 0; i < radio1.length; i++) {
              radio1[i].checked = false;
            }

            //Resetting cleared value of submit button
            $("#lokis-registration-button").val("Submit");

            //Clear error messages
            $("#lokis-error-message").html("");
            $("#lokis-feedback").html(
              "<div class='lokis-loop-success'>" + response.message + "</div>"
            );
            $("#lokis-feedback").show();
          } else {
            //Clear error messages
            $("#lokis-error-message").html("");
            $("#lokis-feedback").html(
              "<div class='lokis-loop-error'>" + response.message + "</div>"
            );

            // Fade out the error message after one second
            setTimeout(function () {
              $(".lokis-loop-error").fadeOut(2000);
            }, 1000);
          }
        },
      });

      // Scroll to the top of the page
      $("html, body").animate({ scrollTop: 0 }, "fast");
    }
  });
});

// Copy Link
jQuery(document).on("click", ".lokisloop-url-copy", function (cp) {
  cp.preventDefault();

  var urlcp = jQuery(this).attr("data-url");
  var $temp = jQuery("<input>");
  jQuery("body").append($temp);
  $temp.val(urlcp).select();

  if (document.execCommand("copy") && $temp.remove())
    alert("URL copied to clipboard");
});

// js code to close modal box
jQuery("body").on("click", ".lokis-modal-close", function (e) {
  e.preventDefault();

  jQuery(".lokis-modal-box").removeClass("is-visible");
});

// code for view-player Modal-box
jQuery(document).ready(function () {
  jQuery("body").on("click", "#lokisLoopModalBox", function (event) {
    event.preventDefault(); // Prevent the default action
    var game_id = jQuery(this).data("game-id"); // Get the URL from the data attribute
    var session_id = jQuery(this).data("session-id"); // Get the URL from the data attribute

    // console.log(game_id);
    jQuery.ajax({
      url: gamesajax.ajaxurl,
      type: "POST",
      data: {
        action: "lokis_loop_modal_box",
        game_id: game_id,
        session_id: session_id,
      },

      success: function (response) {
        jQuery(".lokis_show_modal_box").html(response);
        jQuery(".lokis-modal-box").toggleClass("is-visible");
      },
    });
  });
});

// Function to update profile
jQuery(document).ready(function ($) {
  $("#lokis-profile-update-button").click(function (event) {
    event.preventDefault();

    //Pull data from the form
    var nonce = $("#loki_profile_nonce").val();
    var organization_name = $("#loki-organization").val();
    var organization_type = $(
      "input[name=loki_organization_type]:checked"
    ).val();
    var old_password = $("#loki-old-password").val();
    var new_password = $("#loki-new-password").val();
    var new_password_retype = $("#loki-new-password-retype").val();
    var profile_errors = [];

    //Clearing array if it has any values
    if (profile_errors.length > 0) {
      profile_errors = [];
    }

    //Check if the new passwords matches
    if (new_password !== new_password_retype) {
      profile_errors.push("Passwords do not match");
    }

    if (organization_name === "") {
      profile_errors.push("organization name is empty");
    }

    if (organization_type === "") {
      profile_errors.push("organization name is empty");
    }

    if (profile_errors.length > 0) {
      //Clear div
      $("#lokis-error-message").html("");
      $("#lokis-feedback").html("");

      // Display error messages
      var error_message = "The following errors are shown:<br><ul>";
      for (var i = 0; i < profile_errors.length; i++) {
        error_message += "<li>" + profile_errors[i] + "</li>";
      }
      error_message += "</ul>";
      $("#lokis-error-message").addClass("lokis-loop-incorrect");
      $("#lokis-error-message").css("height", "auto");
      $("#lokis-error-message").html(error_message);
      $("#lokis-error-message").show();

      // Scroll to the top of the page
      $("html, body").animate({ scrollTop: 0 }, "fast");
      return false;
    } else {
      event.preventDefault();

      $.ajax({
        type: "POST",
        url: gamesajax.ajaxurl,
        data: {
          action: "lokis_profile_update",
          organization_name: organization_name,
          organization_type: organization_type,
          old_password: old_password,
          new_password: new_password,
          nonce: nonce,
        },

        success: function (response) {
          if (response.status === "success") {
            //Clear error messages
            $("#lokis-error-message").html("");
            $("#lokis-feedback").html(
              "<div class='lokis-loop-correct'>" + response.message + "</div>"
            );
            $("#lokis-feedback").show();
          } else {
            //Clear error messages
            $("#lokis-error-message").html("");
            $("#lokis-feedback").html(
              "<div class='lokis-loop-incorrect'>" + response.message + "</div>"
            );
            $("#lokis-feedback").show();
          }
        },

        error: function () {
          $("#lokis-error-message").html("");
          $("#lokis-feedback").html(
            "<div class='lokis-loop-incorrect'>The request was not sent</div>"
          );
          $("#lokis-feedback").show();
        },
      });
    }
  });
});

// generate password button
jQuery(document).ready(function ($) {
  $("#generate-password").click(function (event) {
    const passwordLength = 12;
    const characters =
      "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()-_=+[{]}\\|;:'\",.<>/?";
    const password = Array(passwordLength)
      .fill(0)
      .map((e, i) =>
        characters.charAt(Math.floor(Math.random() * characters.length))
      );
    document.getElementById("loki-new-password").value = password.join("");
    document.getElementById("loki-new-password-retype").value =
      document.getElementById("loki-new-password").value;
  });
});

//JS to add active class to active tab in user dashboard menu
jQuery(document).ready(function ($) {
  function setMenuActive() {
    var currentUrl = window.location.href;
    var menuItems = $(".lokisloop-menu li a");

    menuItems.each(function () {
      var menuItemUrl = $(this).attr("href");

      if (
        currentUrl === menuItemUrl ||
        currentUrl.startsWith(menuItemUrl + "/")
      ) {
        $(this).closest("li").addClass("lokis-active");
      } else {
        $(this).closest("li").removeClass("lokis-active");
      }
    });
  }

  // Call the setMenuActive function on page load
  setMenuActive();
});

// JS to generate QR code
function htmlEncode(value) {
  return jQuery("<div/>").text(value).html();
}

jQuery(function ($) {
  $(".lokis-generate-qr").click(function () {
    var qrContent = $(this).siblings(".lokis_qr_content").val();
    var lokis_game_id = $(this).siblings(".lokis_game_id").val();
    var qrCode = $(this).siblings(".lokis-qr-code");
    var generateButton = $(this);

    var qrImageUrl =
      "https://chart.googleapis.com/chart?cht=qr&chl=" +
      htmlEncode(qrContent) +
      "&chs=500x500&chld=L|0";
    qrCode.attr("src", qrImageUrl);
    qrCode.attr("data-qr-url", qrImageUrl);
    qrCode.show();
    generateButton.css("display", "none");
    $('[data-label="QR:"]').css("min-width", "131px");

    // Send AJAX request to save QR code image URL in the database
    var postData = {
      action: "lokis_save_qr_code",
      qrImageUrl: qrImageUrl,
      lokis_game_id: lokis_game_id,
    };

    $.ajax({
      url: gamesajax.ajaxurl, // Replace ajaxurl with the actual URL to your server-side script
      method: "POST",
      data: postData,
      success: function (response) {
        console.log("QR code image URL saved in the database");
      },
      error: function (xhr, status, error) {
        console.error("Error saving QR code image URL in the database:", error);
      },
    });
  });

  $(document).on("click", ".lokis-qr-code", function () {
    var qrUrl = $(this).attr("data-qr-url");
    if (qrUrl) {
      window.open(qrUrl, "_blank");
    }
  });
});

//Function to make the iframe full screen
jQuery(document).ready(function ($) {
  $("#lokis-fullscreen").click(function (event) {
    const iframe = document.getElementById("loki-game-iframe");

    if (iframe.requestFullscreen) {
      iframe.requestFullscreen().then(() => {});
    } else if (iframe.mozRequestFullScreen) {
      // Firefox
      iframe.mozRequestFullScreen();
    } else if (iframe.webkitRequestFullscreen) {
      // Chrome, Safari, Opera
      iframe.webkitRequestFullscreen().then(() => {});
    } else if (iframe.msRequestFullscreen) {
      // IE/Edge
      iframe.msRequestFullscreen().then(() => {});
    }
  });
});

// Check if the query parameter exists
if (location.search.includes("offlinegame")) {
  // Display the modal box
  document.getElementById("lokisOfflineModal").style.display = "block";
}

// JavaScript code to show the popup when the page loads
jQuery(document).ready(function ($) {
  $("#loki-cookie-accept").click(function (event) {
    // Hide the cookie consent popup
    var lokisCookieConsent = document.getElementById("lokisCookieConsent");
    if (lokisCookieConsent) {
      lokisCookieConsent.style.display = "none";
    }

    var session_id = window.location.href.split("?game=")[1];

    // Assign the session ID as the key and current URL as the value in the object
    var urlObj = {};
    urlObj[session_id] = window.location.href;

    $.ajax({
      type: "POST",
      url: gamesajax.ajaxurl,
      data: {
        action: "loki_cookie_maker",
        consent: "accept",
        jsonserializedurl: JSON.stringify(urlObj),
      },
      success: function (response) {
        if (response.status == "success") {
          console.log("cookie successfully made " + session_id + " test user");
        } else {
          console.log("error occurred");
        }
      },
    });
  });

  $("#loki-cookie-reject").click(function (event) {
    // Hide the cookie consent popup
    var lokisCookieConsent = document.getElementById("lokisCookieConsent");
    if (lokisCookieConsent) {
      lokisCookieConsent.style.display = "none";
    }

    $.ajax({
      type: "POST",
      url: gamesajax.ajaxurl,
      data: {
        action: "loki_cookie_maker",
        consent: "reject",
      },
      success: function (response) {
        if (response.status == "reject") {
          console.log("cookie rejected");
        } else {
          console.log("error occurred");
        }
      },
    });
  });
});
