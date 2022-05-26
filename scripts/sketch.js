//----------------------------------------------------------------------------
// not working without api key
if(getCookie("ValidUser")!="true")
throw new Error("User not verefied");

//----------------------------------------------------------------------------

const proportion=0.5; 
var data;
var m1_arrayY;

let  wheel_y;
var btnClicked=false;
var index=2;
//----------------------------------------------------------------------------

sendBtn.onclick= function(){ 
  btnClicked=true; 

  if (animationCheck.checked) {
    // Retrieve calculated values from octave
    data = new FormData(document.querySelector('#param-form'));
    fetch("services/getPlotData.php", {
            method: "POST",
            body: JSON.stringify({
                m1: data.get('m1'),
                m2: data.get('m2'),
                r: data.get('r')
            })
        })
        .then(response => response.json())
        .then(result => {
            
             m1_arrayY = (Object.values(result.y));             

            animationCanvas.classList.remove('hidden');
        })
}

//-----------------------------------------------------------------------------


  // Setup parameters for drawing:
    r="";
    if(data!=null)
      r=data.get('r');  // parameter

    if(r=="")
      r=50;

    r=r*proportion;
    r_abs=Math.abs(r);

    radius=20;

    object_x=50;
    height1_start=height1=50;
    height2_start=height2=120;

    move = 0.5;   // speed of movement

    pit_start_x=80;
    line_y=wheel_y=height-r_abs-20;
    }

   
//-----------------------------------------------------------------------------

    function setup() {  // creates canvas in div "animation-canvas"
      var myCanvas =createCanvas(500, 250);
      myCanvas.parent("animation-canvas");
    }
   

    function draw() {
        if(btnClicked){
            background(200);

            stroke(153);
            // ground:
            line(0,line_y,pit_start_x,line_y); 
            line(pit_start_x,line_y,pit_start_x+r_abs,line_y+r);
            line(pit_start_x+r_abs,line_y+r,width,line_y+r);

            // objects:
            ellipse(object_x+radius, wheel_y-radius, radius*2,radius*2);
            rect(object_x,line_y-radius*2-height1,radius*2,30);
            rect(object_x-radius,line_y-radius*2-height2,radius*4,30);

            // "springs":
            let start_x=object_x+radius;
            let start_y=wheel_y-radius*2;

            line(start_x,start_y ,start_x,line_y-radius*2-height1+30);
            line(start_x,line_y-radius*2-height1 ,start_x,line_y-radius*2-height2+30);



            // movement:
            if(r_abs<=radius*1.5 && animationCheck.checked ){    // if r isn't too big or too small, && user wants to display animation

                pit_start_x=pit_start_x-move;     // move ground, object stays on place

                // restart animation:
                if(pit_start_x<-100 && index>499){
                    pit_start_x=80;
                
                    wheel_y=line_y;
                    height1=height1_start;
                    height2=height2_start;
                    index=2;
                }
                if(r>0 && m1_arrayY!=null && index<500 && object_x+radius>pit_start_x+r_abs){
                 
                  height1=height1+parseFloat(m1_arrayY[index])*proportion;
                  height2=height2+parseFloat(m1_arrayY[index])*proportion*2;  // *2, lebo sa najskor posunie spolu s m1 + ma svoje vlastne posunutie
                  index++;
                }
                if(r<0 && m1_arrayY!=null && index<500 && object_x+radius*1.5>pit_start_x+r_abs){
                 
                  height1=height1+parseFloat(m1_arrayY[index])*proportion;
                  height2=height2+parseFloat(m1_arrayY[index])*proportion*2;
                  index++;
                }

                // animate wheel movement trought obstacle - decide - up or down
                if(pit_start_x+radius/3<object_x+radius && line_y+r>wheel_y){

                    wheel_y=wheel_y+move;
                    height1=height1-move;
                    height2=height2-move;
                    }
                  
                if(pit_start_x-radius/3<object_x+radius && line_y+r<wheel_y ){
                    
                    wheel_y=wheel_y-move;
                    height1=height1+move;
                    height2=height2+move;
                  }
            
              }
        }
      }

