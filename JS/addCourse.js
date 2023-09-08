document.addEventListener("DOMContentLoaded", function () {
  const addExistingCourseform = document.getElementById(
    "addExistingCourseForm"
  );
  addExistingCourseform.addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent the default form submission

    const formData = new FormData(addExistingCourseform);
    const xhr = new XMLHttpRequest();
    const url = "php/add_course.php"; //

    const urlObject = new URL(window.location.href);

    // Get the query parameters as a URLSearchParams object
    const searchParams = new URLSearchParams(urlObject.search);

    // Extract individual query parameters
    const code = searchParams.get("code");
    const year = searchParams.get("year");
    const intake = searchParams.get("intake");
    formData.append("code", code);
    formData.append("year", year);
    formData.append("intake", intake);
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
          console.error(xhr.statusText);
          // You can show an error message here
        }
      }
    };

    xhr.send(formData);
  });
});

// new course
document.addEventListener("DOMContentLoaded", function () {
  const addNewCourseForm = document.getElementById("addNewCourseForm");
  addNewCourseForm.addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent the default form submission

    const formData = new FormData(addNewCourseForm);
    const xhr = new XMLHttpRequest();
    const url = "php/add_new_course.php"; //

    const urlObject = new URL(window.location.href);

    // Get the query parameters as a URLSearchParams object
    const searchParams = new URLSearchParams(urlObject.search);

    // Extract individual query parameters
    const code = searchParams.get("code");
    const year = searchParams.get("year");
    const intake = searchParams.get("intake");
    formData.append("code", code);
    formData.append("year", year);
    formData.append("intake", intake);
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
