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
    var errors = 0;

    var nameErrorDiv = $("#lokis-names .lokis-error");
    var emailErrorDiv = $("#lokis-email .lokis-error");
    var organizationErrorDiv = $("#lokis-organization .lokis-error");
    var organizationTypeErrorDiv = $("#lokis-organization-type .lokis-error");
    var countryErrorDiv = $("#lokis-country .lokis-error");

    //Clearing array if it has any values
    if (errors > 0) {
      errors = 0;
    }

    nameErrorDiv.remove();
    emailErrorDiv.remove();
    organizationErrorDiv.remove();
    organizationTypeErrorDiv.remove();
    countryErrorDiv.remove();

    if (name == "") {
      var errorMessage = $("<div class='lokis-error'>")
        .addClass("lokis-error")
        .text("Name is required.");
      $("#lokis-names").append(errorMessage);
      errors = errors + 1;
    }

    // Validate email format
    var email_pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email == "") {
      var errorMessage = $("<div class='lokis-error'>")
        .addClass("lokis-error")
        .text("Email is required.");
      $("#lokis-email").append(errorMessage);
      errors = errors + 1;
    } else if (!email_pattern.test(email)) {
      var errorMessage = $("<div class='lokis-error'>")
        .addClass("lokis-error")
        .text("Please enter a valid email address.");
      $("#lokis-email").append(errorMessage);
      errors = errors + 1;
    }

    if (organization_name == "") {
      var errorMessage = $("<div class='lokis-error'>")
        .addClass("lokis-error")
        .text("Organization name is required.");
      $("#lokis-organization").append(errorMessage);
      errors = errors + 1;
    }

    if (organization_type == undefined) {
      var errorMessage = $("<div class='lokis-error'>")
        .addClass("lokis-error")
        .text("Please select your organization type.");
      $("#lokis-organization-type").append(errorMessage);
      errors = errors + 1;
    }

    if (country_name == "") {
      var errorMessage = $("<div class='lokis-error'>")
        .addClass("lokis-error")
        .text("Country is required.");
      $("#lokis-country").append(errorMessage);
      errors = errors + 1;
    }

    if (errors > 0) {
      var emailInput = $("#loki-email");
      var nameInput = $("#loki-name");
      var organizationInput = $("#loki-organization");
      var organizationTypeInput = $("input[name=loki_organization_type]");
      var countryInput = $("#loki-country");

      var nameErrorDiv = $("#lokis-names .lokis-error");
      var emailErrorDiv = $("#lokis-email .lokis-error");
      var organizationErrorDiv = $("#lokis-organization .lokis-error");
      var organizationTypeErrorDiv = $("#lokis-organization-type .lokis-error");
      var countryErrorDiv = $("#lokis-country .lokis-error");

      nameInput.on("input", function () {
        // Clear error message div
        nameErrorDiv.remove();
      });

      emailInput.on("input", function () {
        // Clear error message div
        emailErrorDiv.remove();
      });

      organizationInput.on("input", function () {
        // Clear error message div
        organizationErrorDiv.remove();
      });

      organizationTypeInput.on("change", function () {
        // Clear error message div
        organizationTypeErrorDiv.remove();
      });

      countryInput.on("change", function () {
        // Clear error message div
        countryErrorDiv.remove();
      });

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

            if (response.type == "name") {
              $("#loki-name").on("input", function () {
                // Clear error message div
                $("#lokis-feedback .lokis-loop-error").remove();
              });
            } else if (response.type == "email") {
              $("#loki-email").on("input", function () {
                // Clear error message div
                $("#lokis-feedback .lokis-loop-error").remove();
              });
            } else {
              $("#loki-zipcode").on("input", function () {
                // Clear error message div
                $("#lokis-feedback .lokis-loop-error").remove();
              });
            }
          }
        },
      });
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
    event.preventDefault();
    var game_id = jQuery(this).data("game-id");
    var session_id = jQuery(this).data("session-id");

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

    var organizationErrorDiv = $("#lokis-organization .lokis-error");
    var organizationTypeErrorDiv = $("#lokis-organization-type .lokis-error");
    var passwordErrorDiv = $("#lokis-password .lokis-error");

    organizationErrorDiv.remove();
    organizationTypeErrorDiv.remove();
    passwordErrorDiv.remove();

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
      var errorMessage = $("<div class='lokis-error'>")
        .addClass("lokis-error")
        .text("The passwords do not match");
      $("#lokis-password").append(errorMessage);
      profile_errors = profile_errors + 1;
    }

    if (organization_name === "") {
      var errorMessage = $("<div class='lokis-error'>")
        .addClass("lokis-error")
        .text("organization name is empty");
      $("#lokis-organization").append(errorMessage);
      profile_errors = profile_errors + 1;
    }

    if (organization_type === undefined) {
      var errorMessage = $("<div class='lokis-error'>")
        .addClass("lokis-error")
        .text("organization type is empty");
      $("#lokis-organization-type").append(errorMessage);
      profile_errors = profile_errors + 1;
    }

    if (profile_errors.length > 0) {
      //Clear div
      $("#lokis-feedback").html("");

      var organizationInput = $("#loki-organization");
      var organizationTypeInput = $("input[name=loki_organization_type]");
      var passwordInput = $("#loki-new-password");

      var organizationErrorDiv = $("#lokis-organization .lokis-error");
      var organizationTypeErrorDiv = $("#lokis-organization-type .lokis-error");
      var passwordErrorDiv = $("#lokis-password .lokis-error");

      organizationInput.on("input", function () {
        // Clear error message div
        organizationErrorDiv.remove();
      });

      organizationTypeInput.on("change", function () {
        // Clear error message div
        organizationTypeErrorDiv.remove();
      });

      passwordInput.on("input", function () {
        // Clear error message div
        passwordErrorDiv.remove();
      });

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
            setTimeout(function () {
              window.location.reload();
            }, 1000);
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
      url: gamesajax.ajaxurl,
      method: "POST",
      data: postData,
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

//Function to move forgot password link
jQuery(document).ready(function ($) {
  passwordDiv = $(".login-password label");
  linkDiv = $(".lokis-loginPage");

  if (linkDiv.length > 0) {
    linkDiv.insertAfter(passwordDiv);
  }
});

//Function to add lokis-user-dashboard class in body part when the page reaches user dashboard
jQuery(document).ready(function ($) {
  var currentUrl = window.location.href;

  // Function to check if the current page is the user dashboard
  function isUserDashboardPage(url) {
    return url.indexOf("user-dashboard") !== -1;
  }

  // Check if the current page is the user dashboard and add the class to the body tag
  if (isUserDashboardPage(currentUrl)) {
    $("body").addClass("lokis-user-dashboard");
  }
});

//Check whether the current page is a game stage and add the class to the body tag
jQuery(document).ready(function ($) {
  var currentUrl = window.location.href;

  // Function to check if the current page is a games post type page
  function isGamesPostTypePage(url) {
    return url.indexOf("/games/") !== -1;
  }

  // Check if the current page is a games post type page and add the class to the body tag
  if (isGamesPostTypePage(currentUrl)) {
    $("body").addClass("lokis-games-stages");
  }
});

//Function to add game session id at the end of game stage url
jQuery(document).ready(function ($) {
  $(document).on("click", "#lokis-start-game", function (event) {
    // Get the URL of the current page
    var url = window.location.href;

    var expiryTime = new Date();
    expiryTime.setTime(expiryTime.getTime() + 60000); // 1 minute in milliseconds

    // Define the query variable name
    var queryVarName = "game";
    var altQuery = "offlinegame";

    // Create a regex pattern to match the query variable and its value
    var regexPattern = new RegExp("[?&]" + queryVarName + "=([^&#]*)");
    var altRegexPattern = new RegExp("[?&]" + altQuery + "=([^&#]*)");

    var isGameQueryVar = regexPattern.test(url);
    var altGameVar = altRegexPattern.test(url);

    if (isGameQueryVar) {
      // Use the regex pattern to extract the query variable value
      var matches = regexPattern.exec(url);

      // Check if a match is found and retrieve the value
      if (matches && matches.length > 1) {
        var gameValue = matches[1];

        event.preventDefault(); // Prevent the default link behavior

        var href = $(this).attr("href"); // Get the href attribute of the clicked link
        var modifiedHref = href + "?game=" + gameValue; // Append the desired text to the URL

        // Redirect the user to the modified URL
        window.location.href = modifiedHref;
      }
    } else if (altGameVar) {
      // Use the regex pattern to extract the query variable value
      var matches = altRegexPattern.exec(url);

      // Check if a match is found and retrieve the value
      if (matches && matches.length > 1) {
        var gameValue = matches[1];

        event.preventDefault(); // Prevent the default link behavior

        var href = $(this).attr("href"); // Get the href attribute of the clicked link
        var modifiedHref = href + "?offlinegame=" + gameValue; // Append the desired text to the URL
        
        // Redirect the user to the modified URL
        window.location.href = modifiedHref;
      }
    }
  });
});

// Check if the query parameter exists
if (location.search.includes("offlinegame")) {
  // Display the modal box
  document.getElementById("lokisOfflineModal").style.display = "block";
}
