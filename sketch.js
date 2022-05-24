let r=-23;
let r_abs=Math.abs(r);
let radius=20;
let object_x=50;
let height1=50;
let height2=120;
let wheel_y;

function setup() {
  var myCanvas =createCanvas(720, 250);
  myCanvas.parent("canvas_div");
  pit_start_x=100;
  pit_x_width=radius*2+20;
  line_y=wheel_y=height-r_abs-20;

}

function draw() {
  background(200);
  
  stroke(153);
  line(0,line_y,pit_start_x,line_y);
  line(pit_start_x,line_y,pit_start_x+r_abs,line_y+r);
  line(pit_start_x+r_abs,line_y+r,pit_start_x+pit_x_width,line_y+r);
  line(pit_start_x+pit_x_width,line_y+r,pit_start_x+pit_x_width+r_abs,line_y);
  line(pit_start_x+pit_x_width+r_abs,line_y,720,line_y);
  
  ellipse(object_x+radius, wheel_y-radius, radius*2,radius*2);
  rect(object_x,line_y-radius*2-height1,radius*2,30);
  rect(object_x-radius,line_y-radius*2-height2,radius*4,30);
  
  let start_x=object_x+radius;
  let start_y=line_y-radius;
   for(let posY = 0; posY < 5; posY++) {
      //from wheel to first object
     
     //line(start_x,start_y ,)
    }
  
  
  if(r<=radius*1.2){
    
  pit_start_x=pit_start_x-0.5;
  if(pit_start_x<-100){
    pit_start_x=100;
  }
  
  if(pit_start_x+radius/3<object_x+radius && line_y+r>wheel_y){
    wheel_y=wheel_y+0.5;
  }
  if(pit_start_x+pit_x_width>object_x-radius && line_y<wheel_y){
    //wheel_y=wheel_y-0.5;
  }
  }
}
