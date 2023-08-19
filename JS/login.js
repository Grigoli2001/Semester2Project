
var close_btn = document.getElementById('closebut')
var error_msg = document.getElementById('error')
if (close_btn){
close_btn.addEventListener('click',() => {
    error_msg.style.visibility = 'hidden' 
})
}



var password = document.getElementById("reg_password")

var conf_password = document.getElementById("conf_password");
var conf_password_span = document.getElementById("conf_password_span")
function check_pass(){
    if(password.value === conf_password.value){
        conf_password_span.style.top = '30px';
        conf_password_span.style.color = 'green';
    }else{
        conf_password_span.style.top = '30px';
        conf_password_span.style.color = 'red';

}

}
// if (card[0].style.visibility = "visible"){
//     container[0].addEventListener('click',()=> {
//         card[0].style.visibility = "hidden"
//         container[0].style.filter = "blur(0px)"
// })
// }
