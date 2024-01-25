// function that changes the pictures
let avt = document.getElementsByClassName('avt');

for(let i=0 ; i <avt.length;i++){
avt[i].style.zIndex = avt.length-i;

}
let j =1;

function lami(x){
if(x.value == 0 || x==""){
  avt[0].style.opacity = 0;
}else { 
  avt[j].style.opacity = 0;
 
  if(j==5)
  { 
    for(let i=0 ; i <avt.length;i++){
      avt[i].style.opacity   = 1;
  }
  j=0;
}else{
  j++;
}
}
if(x=='r')
{
  for(let i=0 ; i <avt.length;i++){
    avt[i].style.opacity   = 1;
}
}
if(x=='p')
{
  for(let i=0 ; i <avt.length;i++){
    avt[i].style.opacity   = 0;
}
avt[avt.length-1].style.opacity   = 1;

}
}












