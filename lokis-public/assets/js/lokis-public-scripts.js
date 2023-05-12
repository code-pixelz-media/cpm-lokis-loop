//sending ajax post to wordpress to check answer from given data
jQuery(document).ready(function ($) {
  $("#lokis-submit-btn").click(function (event) {
    event.preventDefault();
    var answer = $("#lokis-answer").val();
    var post_id = $("#loki-post-id").val();

    $.ajax({
      type: "POST",
      url: gamesajax.ajaxurl,
      data: {
        action: "lokis_check_answer",
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
        }
      },
    });
  });
});

//Form validation to check input and email format
jQuery(document).ready(function ($) {
  $("#lokis-registration-button").click(function (event) {
    var name = $("#loki-name").val();
    var email = $("#loki-email").val();
    var organization_name = $("#loki-organization").val();
    var organization_type = $(
      "input[name=loki_organization_type]:checked"
    ).val();
    var country_name = $("#loki-country").val();
    var role = $("input[name=role]:checked").val();
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

    if (role == undefined) {
      errors.push("Please select your role.");
    }

    if (zipcode == "") {
      errors.push("Zipcode is required.");
    }

    if (errors.length > 0) {
      //Clear div
      $("#error-message").html("");
      $("#lokis-feedback").html("");

      // Display error messages
      var error_message = "Please correct the following errors:<br><ul>";
      for (var i = 0; i < errors.length; i++) {
        error_message += "<li>" + errors[i] + "</li>";
      }
      error_message += "</ul>";
      $("#error-message").addClass("lokis-loop-incorrect");
      $("#error-message").css("height", "auto");
      $("#error-message").html(error_message);
      $("#error-message").show();

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
          role: role,
          zipcode: zipcode,
          nonce: nonce,
        },
        success: function (response) {
          if (response.status === "success") {
            $("#error-message").html("");
            $("#lokis-feedback").html(
              "<div class='lokis-loop-correct'>" + response.message + "</div>"
            );
            $("#lokis-feedback").show();
          } else {
            $("#error-message").html("");
            $("#lokis-feedback").html(
              "<div class='lokis-loop-incorrect'>" + response.message + "</div>"
            );
            $("#lokis-feedback").show();
          }
        },
      });
      // Scroll to the top of the page
      $("html, body").animate({ scrollTop: 0 }, "fast");
    }
  });
});
