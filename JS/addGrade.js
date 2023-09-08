document.addEventListener("DOMContentLoaded", function () {
  const addGradeForm = document.getElementById("addGradeForm");
  console.log(addGradeForm);
  addGradeForm.addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent the default form submission

    const formData = new FormData(addGradeForm);
    const xhr = new XMLHttpRequest();
    const url = "php/add_grade.php"; //

    const urlObject = new URL(window.location.href);

    // Get the query parameters as a URLSearchParams object
    const searchParams = new URLSearchParams(urlObject.search);

    // Extract individual query parameters
    const code = searchParams.get("code");
    const year = searchParams.get("year");
    const intake = searchParams.get("intake");
    const courseName = searchParams.get("course_name");
    formData.append("code", code);
    formData.append("year", year);
    formData.append("intake", intake);
    formData.append("courseName", courseName);
    xhr.open("POST", url, true);

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          // Handle success response
          window.location.reload();
          console.log("Im working ");
          // You can update the page content or show a success message here
        } else {
          // Handle error response
          console.error(xhr.statusText);
          // You can show an error message here
        }
      }
    };

    xhr.send(formData);
  });
});
