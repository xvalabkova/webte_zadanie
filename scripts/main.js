

document.getElementById("htmlData").value=document.getElementById("htmldata").outerHTML;

document.getElementsByClassName("close")[0].onclick = function() {
document.getElementById("modal").style.display = "none";


}
document.getElementsByClassName("close")[1].onclick = function() {
 
  document.getElementById("modal2").style.display = "none";
  
  }
window.onclick = function(event) {
if (event.target == modal) {
document.getElementById("modal").style.display = "none";
document.getElementById("modal2").style.display = "none";
}
  }

document.getElementById("placeholder2").onclick = function() {
  document.getElementById("modal").style.display = "block";
  } 

/*function show() { 
  console.log("show");
  document.getElementById("modal").style.display = "block";
          } */
  




  
  
    
  

            
      
    