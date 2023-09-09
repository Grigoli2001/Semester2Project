const navbarHeight = document.getElementById("navbar").offsetHeight;

// Set the top margin of the .container_home class
const containerPop = document.querySelector(".container");
const welcome = document.querySelector(".welcome");
if (containerPop) {
  containerPop.style.marginTop = `${navbarHeight + 10}px`;
}
console.log(containerPop, welcome);
if (welcome) {
  welcome.style.marginTop = `${navbarHeight + 10}px`;
}
