let  wheel_y;
const proportion=0.5;
var btnClicked=false;
const formElementHeight=document.querySelector('#height');
//-----------------------------------------------------------------------------

sendBtn.onclick= function(){ 
  btnClicked=true; 
  //do my own setup:
  r=50;
  if(formElementHeight.value!="")
  r=formElementHeight.value;  // parameter

  r=r*proportion;
 r_abs=Math.abs(r);

 radius=20;

 object_x=50;
 height1_start=height1=50;
 height2_start=height2=120;

 move = 0.5;

 pit_start_x=100;
 line_y=wheel_y=height-r_abs-20;
    }

//-----------------------------------------------------------------------------

    function setup() {
    var myCanvas =createCanvas(600, 250);
   myCanvas.parent("animation-canvas");
   }
   
    function draw() {
      if(btnClicked){
     background(200);
     
     stroke(153);
     line(0,line_y,pit_start_x,line_y); 
     line(pit_start_x,line_y,pit_start_x+r_abs,line_y+r);
     line(pit_start_x+r_abs,line_y+r,width,line_y+r);
     
     ellipse(object_x+radius, wheel_y-radius, radius*2,radius*2);
     rect(object_x,line_y-radius*2-height1,radius*2,30);
     rect(object_x-radius,line_y-radius*2-height2,radius*4,30);
     
     
     let start_x=object_x+radius;
     let start_y=wheel_y-radius*2;

    line(start_x,start_y ,start_x,line_y-radius*2-height1+30);
    line(start_x,line_y-radius*2-height1 ,start_x,line_y-radius*2-height2+30);

      for(let posY = 0; posY < 5; posY++) {
         //from wheel to first object
        //line(start_x,start_y ,)
       }

     
     if(r_abs<=radius*1.5 && animationCheck.checked && btnClicked ){
       
     pit_start_x=pit_start_x-move;
     if(pit_start_x<-100){
        pit_start_x=100;
        wheel_y=line_y;
        height1=height1_start;
        height2=height2_start;
     }
     
     if(pit_start_x+radius/3<object_x+radius && 
        line_y+r>wheel_y){
        wheel_y=wheel_y+move;
       height1=height1-move;
         height2=height2-move;
        }
        
       if(pit_start_x-radius/3<object_x+radius && 
        line_y+r<wheel_y ){
         
         wheel_y=wheel_y-move;
         height1=height1+move;
       height2=height2+move;
       }
     
     }
   }
  }

