const navbarHeight = document.getElementById("navbar").offsetHeight;

// Set the top margin of the .container_home class
const containerPop = document.querySelector(".container");
containerPop.style.marginTop = `${navbarHeight + 10}px`;
