const sendEmailPopup = document.getElementById("sendEmailPopup");
const sendEmailButton = document.getElementById("bt");
const sendEmailCloseImg = document.getElementById("add_course_popup_close_img");
const sendEmailForm = document.getElementById("sendEmailForm");
sendEmailButton.addEventListener("click", () => {
  sendEmailPopup.classList.add("show");
});
sendEmailCloseImg.addEventListener("click", () => {
  sendEmailPopup.classList.remove("show");
});
sendEmailForm.addEventListener("submit", (event) => {
  event.preventDefault();
  const formData = new FormData(sendEmailForm);
  const xhr = new XMLHttpRequest();
  const url = "php/send_email.php";
  const urlObject = new URL(window.location.href);
  const searchParams = new URLSearchParams(urlObject.search);
  const email = searchParams.get("email");
  const subject = formData.get("subject");
  const message = formData.get("message");
  console.log(email, subject, message);
  formData.append("email", email);
  if (!subject || !message) {
    alert("please fill all the fields");
  }
  xhr.open("POST", url, true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        // Handle success response
        console.log(xhr.responseText);
        window.location.reload();
        console.log("Im working ");
        // You can update the page content or show a success message here
      } else {
        // Handle error response
        console.error(xhr.responseText);

        // You can show an error message here
      }
    }
  };

  xhr.send(formData);
});
