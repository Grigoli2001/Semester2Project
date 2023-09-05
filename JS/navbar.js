const navbarHeight = document.getElementById("navbar").offsetHeight;

// Set the top margin of the .container_home class
const containerPop = document.querySelector(".container");
const welcome = document.querySelector(".welcome");
containerPop.style.marginTop = `${navbarHeight + 10}px`;
welcome.style.marginTop = `${navbarHeight + 10}px`;
