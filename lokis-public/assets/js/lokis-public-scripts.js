// creating js for game host application form
let email_regex =
  /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

function validate() {
  let u_name = document.getElementById("name").value;
  let u_email = document.getElementById("email").value;
  let u_organization = document.getElementById("organization").value;
  let u_country = document.getElementById("country-name").value;
  let u_role = document.getElementById("role").value;
  let u_zipcode = document.getElementById("zipcode").value;
}

//validate email address usign regex
if (u_email.match(email_regex)) {
  //check for valid url
  try {
    let u_url = new URL(u_website);
    document.getElementById("Submit-button").classList.remove("disabled"); //button enabled
    document.getElementById("err_msg").classList.remove("text-danger");
    document.getElementById("err_msg").classList.add("text-success");
    err_msg.innerHTML = "Form ready for submission!!";
  } catch (error) {
    document.getElementById("Submit-button").classList.add("disabled");
    err_msg.innerHTML = err_prefix + "Invalid email!!";
  }
} else {
  document.getElementById("Submit-button").classList.add("disabled");
  err_msg.innerHTML = err_prefix + "All fields must be filled !!";
}
// }
