// Generated Code for the Draw2D touch HTML5 lib
//                                                        
// http://www.draw2d.org                                  
//                                                        
// Go to the Designer http://www.draw2d.org               
// to design your own shape or download user generated    
//                                                        
var transceiver = draw2d.SetFigure.extend({            

    NAME: "transceiver",
 
    init:function(attr, setter, getter)
    {
      this._super( $.extend({stroke:0, bgColor:null, width:422,height:385},attr), setter, getter);
      var port;
      // Port
      port = this.createPort("input", new draw2d.layout.locator.XYRelPortLocator(98.81516587677726, 30.909090909090907));
      port.setConnectionDirection();
      port.setBackgroundColor("#37B1DE");
      port.setName("PIN3");
      port.setMaxFanOut(1);
      // Port
      port = this.createPort("input", new draw2d.layout.locator.XYRelPortLocator(98.81516587677726, 36.62337662337662));
      port.setConnectionDirection();
      port.setBackgroundColor("#37B1DE");
      port.setName("PIN4");
      port.setMaxFanOut(1);
      // Port
      port = this.createPort("input", new draw2d.layout.locator.XYRelPortLocator(98.81516587677726, 41.55844155844156));
      port.setConnectionDirection();
      port.setBackgroundColor("#37B1DE");
      port.setName("PIN5");
      port.setMaxFanOut(1);
      // Port
      port = this.createPort("input", new draw2d.layout.locator.XYRelPortLocator(98.81516587677726, 47.01298701298701));
      port.setConnectionDirection();
      port.setBackgroundColor("#37B1DE");
      port.setName("PIN6");
      port.setMaxFanOut(1);
      // Port
      port = this.createPort("input", new draw2d.layout.locator.XYRelPortLocator(98.81516587677726, 52.467532467532465));
      port.setConnectionDirection();
      port.setBackgroundColor("#37B1DE");
      port.setName("PIN7");
      port.setMaxFanOut(1);
      // Port
      port = this.createPort("input", new draw2d.layout.locator.XYRelPortLocator(98.81516587677726, 57.92207792207792));
      port.setConnectionDirection();
      port.setBackgroundColor("#37B1DE");
      port.setName("PIN8");
      port.setMaxFanOut(1);
      // Port
      port = this.createPort("input", new draw2d.layout.locator.XYRelPortLocator(98.81516587677726, 62.857142857142854));
      port.setConnectionDirection();
      port.setBackgroundColor("#37B1DE");
      port.setName("PIN9");
      port.setMaxFanOut(1);
      // Port
      port = this.createPort("input", new draw2d.layout.locator.XYRelPortLocator(98.81516587677726, 68.3116883116883));
      port.setConnectionDirection();
      port.setBackgroundColor("#37B1DE");
      port.setName("PIN10");
      port.setMaxFanOut(1);
      this.persistPorts=false;
    },
 
    createShapeElement : function()
    {
       var shape = this._super();
       this.originalWidth = 422;
       this.originalHeight= 385;
       return shape;
    },
 
    createSet: function()
    {
        this.canvas.paper.setStart();
 
         // BoundingBox
         shape = this.canvas.paper.path("M0,0 L422,0 L422,385 L0,385");
         shape.attr({"stroke":"none","stroke-width":0,"fill":"none"});
         shape.data("name","BoundingBox");
         
         // Rectangle
         shape = this.canvas.paper.path('M0,10Q0,0 10, 0L407,0Q417,0 417, 10L417,328Q417,338 407, 338L10,338Q0,338 0, 328L0,10');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#026acf","dasharray":null,"opacity":0.8});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M8 16L111 16L111 200L8 200Z');
         shape.attr({"stroke":"#FFFFFF","stroke-width":3,"fill":"#303030","dasharray":null,"opacity":0.8});
         shape.data("name","Rectangle");      
         
         // Rectangle
         shape = this.canvas.paper.path('M47 330L132 330L132 269L47 269Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#FFFFFF","dasharray":null,"opacity":0.25});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M308 337L151 337L151 3L308 3Z');
         shape.attr({"stroke":"#F5F5F5","stroke-width":3,"fill":"#026acf","dasharray":null,"opacity":0.35});
         shape.data("name","Rectangle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":301,"cy":20,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":301,"cy":40,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":301,"cy":60,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":301,"cy":80,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":301,"cy":100,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":301,"cy":120,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle"); 
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":301,"cy":140,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":301,"cy":160,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":301,"cy":180,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle"); 
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":301,"cy":200,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":301,"cy":220,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle"); 
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":301,"cy":240,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":301,"cy":260,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":301,"cy":280,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle"); 
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":301,"cy":300,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");                         

         // Rectangle
         shape = this.canvas.paper.path('M260 121L205 121L205 87L260 87Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#FFFFFF","dasharray":null,"opacity":0.45});
         shape.data("name","Rectangle");
         
         // Label
         shape = this.canvas.paper.text(0,0,'RESET');
         shape.attr({"x":210,"y":77,"text-anchor":"start","text":"RESET","font-family":"\"Arial\"","font-size":14,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Rectangle
         shape = this.canvas.paper.path('M196 300L263 300L263 385L196 385Z');
         shape.attr({"stroke":"#C0C0C0","stroke-width":1,"fill":"#303030","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":9,"ry":9,"cx":232.5,"cy":104,"stroke":"#A3A3A3","stroke-width":2,"fill":"#FFFFFF","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Label
         shape = this.canvas.paper.text(0,0,'USB');
         shape.attr({"x":203.640625,"y":339,"text-anchor":"start","text":"USB","font-family":"\"Arial\"","font-size":25,"stroke":"#FF0000","fill":"#FFFFFF","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":157,"cy":20,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":157,"cy":40,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":157,"cy":60,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":157,"cy":80,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":157,"cy":100,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":157,"cy":120,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":157,"cy":140,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":157,"cy":160,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":157,"cy":180,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":157,"cy":200,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":157,"cy":220,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":157,"cy":240,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":157,"cy":260,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":157,"cy":280,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":157,"cy":300,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");                                           
        
         // Rectangle
         shape = this.canvas.paper.path('M345 34L327 34L327 11L345 11Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#FFFFFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M351 11L369 11L369 34L351 34Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#FFFFFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M375 11L393 11L393 34L375 34Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#FFFFFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":2.5,"ry":2.5,"cx":336,"cy":23,"stroke":"none","stroke-width":0,"fill":"#C00000","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":2.5,"ry":2.5,"cx":361,"cy":23.5,"stroke":"none","stroke-width":0,"fill":"#C00000","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":2.5,"ry":2.5,"cx":384.5,"cy":23,"stroke":"none","stroke-width":0,"fill":"#C00000","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Label
         shape = this.canvas.paper.text(0,0,'+5V');
         shape.attr({"x":334,"y":49,"text-anchor":"start","text":"+5V","font-family":"\"Arial\"","font-size":18,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":417,"cy":99,"stroke":"none","stroke-width":0,"fill":"#000000","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5,"ry":5,"cx":417,"cy":79,"stroke":"none","stroke-width":0,"fill":"#C00000","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Rectangle
         shape = this.canvas.paper.path('M328 303L346 303L346 326L328 326Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#FFFFFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M351 303L369 303L369 326L351 326Z');
         shape.attr({"stroke":"#000000","stroke-width":1,"fill":"#FFFFFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M375 303L393 303L393 326L375 326Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#FFFFFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":2.5,"ry":2.5,"cx":336.5,"cy":315,"stroke":"none","stroke-width":0,"fill":"#000000","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":2.5,"ry":2.5,"cx":361,"cy":315.5,"stroke":"none","stroke-width":0,"fill":"#000000","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":2.5,"ry":2.5,"cx":384.5,"cy":315.5,"stroke":"none","stroke-width":0,"fill":"#000000","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Label
         shape = this.canvas.paper.text(0,0,'GND');
         shape.attr({"x":335,"y":291,"text-anchor":"start","text":"GND","font-family":"\"Arial\"","font-size":18,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'+5V');
         shape.attr({"x":381,"y":80,"text-anchor":"start","text":"+5V","font-family":"\"Arial\"","font-size":14,"stroke":"none","fill":"#444","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'GND');
         shape.attr({"x":376,"y":100,"text-anchor":"start","text":"GND","font-family":"\"Arial\"","font-size":14,"stroke":"none","fill":"#444","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'PIN3');
         shape.attr({"x":375,"y":120,"text-anchor":"start","text":"PIN3","font-family":"\"Arial\"","font-size":14,"stroke":"none","fill":"#444","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'PIN4');
         shape.attr({"x":375,"y":140,"text-anchor":"start","text":"PIN4","font-family":"\"Arial\"","font-size":14,"stroke":"none","fill":"#444","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'PIN5');
         shape.attr({"x":375,"y":160,"text-anchor":"start","text":"PIN5","font-family":"\"Arial\"","font-size":14,"stroke":"none","fill":"#444","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'PIN6');
         shape.attr({"x":375,"y":180,"text-anchor":"start","text":"PIN6","font-family":"\"Arial\"","font-size":14,"stroke":"none","fill":"#444","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'PIN7');
         shape.attr({"x":375,"y":202,"text-anchor":"start","text":"PIN7","font-family":"\"Arial\"","font-size":14,"stroke":"none","fill":"#444","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'PIN8');
         shape.attr({"x":374,"y":222,"text-anchor":"start","text":"PIN8","font-family":"\"Arial\"","font-size":14,"stroke":"none","fill":"#444","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'PIN9');
         shape.attr({"x":374,"y":241,"text-anchor":"start","text":"PIN9","font-family":"\"Arial\"","font-size":14,"stroke":"none","fill":"#444","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'PIN10');
         shape.attr({"x":367,"y":262,"text-anchor":"start","text":"PIN10","font-family":"\"Arial\"","font-size":14,"stroke":"none","fill":"#444","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":13,"ry":13,"cx":71,"cy":306,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":13,"ry":13,"cx":109,"cy":306,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":6,"ry":6,"cx":42,"cy":29,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":6,"ry":6,"cx":61,"cy":29,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":6,"ry":6,"cx":81,"cy":29,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":6,"ry":6,"cx":100,"cy":29,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":6,"ry":6,"cx":100,"cy":55,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":6,"ry":6,"cx":81,"cy":55,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":6,"ry":6,"cx":61,"cy":55,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":6,"ry":6,"cx":42,"cy":55,"stroke":"none","stroke-width":0,"fill":"#C0C0C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
 
         return this.canvas.paper.setFinish();
    },
 
    applyAlpha: function()
    {
    },
 
    layerGet: function(name, attributes)
    {
       if(this.svgNodes===null) return null;
 
       var result=null;
       this.svgNodes.some(function(shape){
          if(shape.data("name")===name){
             result=shape;
          }
          return result!==null;
       });
 
       return result;
    },
 
    layerAttr: function(name, attributes)
    {
      if(this.svgNodes===null) return;
 
      this.svgNodes.forEach(function(shape){
              if(shape.data("name")===name){
                   shape.attr(attributes);
              }
      });
    },
 
    layerShow: function(name, flag, duration)
    {
       if(this.svgNodes===null) return;
 
       if(duration){
         this.svgNodes.forEach(function(node){
             if(node.data("name")===name){
                 if(flag){
                     node.attr({ opacity : 0 }).show().animate({ opacity : 1 }, duration);
                 }
                 else{
                     node.animate({ opacity : 0 }, duration, function () { this.hide() });
                 }
             }
         });
       }
       else{
           this.svgNodes.forEach(function(node){
               if(node.data("name")===name){
                    if(flag){node.show();}
                    else{node.hide();}
                }
            });
       }
    },
 
     calculate: function()
     {
     },
 
     onStart: function()
     {
     },
 
     onStop:function()
     {
     },
 
     getParameterSettings: function()
     {
         return [];
     },
 
     /**
      * @method
      */
     addPort: function(port, locator)
     {
         this._super(port, locator);
         return port;
     },
 
     /**
      * @method
      * Return an objects with all important attributes for XML or JSON serialization
      *
      * @returns {Object}
      */
     getPersistentAttributes : function()
     {
         var memento = this._super();
 
         // add all decorations to the memento
         //
         memento.labels = [];
         this.children.each(function(i,e){
             var labelJSON = e.figure.getPersistentAttributes();
             labelJSON.locator=e.locator.NAME;
             memento.labels.push(labelJSON);
         });
 
         return memento;
     },
 
     /**
      * @method
      * Read all attributes from the serialized properties and transfer them into the shape.
      *
      * @param {Object} memento
      * @returns
      */
     setPersistentAttributes : function(memento)
     {
         this._super(memento);
 
         // remove all decorations created in the constructor of this element
         //
         this.resetChildren();
 
         // and add all children of the JSON document.
         //
         $.each(memento.labels, $.proxy(function(i,json){
             // create the figure stored in the JSON
             var figure =  eval("new "+json.type+"()");
 
             // apply all attributes
             figure.attr(json);
 
             // instantiate the locator
             var locator =  eval("new "+json.locator+"()");
 
             // add the new figure as child to this figure
             this.add(figure, locator);
         },this));
     }
 });
 
 /**
  * by 'Draw2D Shape Designer'
  *
  * Custom JS code to tweak the standard behaviour of the generated
  * shape. add your custome code and event handler here.
  *
  *
  */
 transceiver = transceiver.extend({
 
     init: function(attr, setter, getter){
          this._super(attr, setter, getter);
 
          // your special code here
     },
 
     /**
      *  Called by the simulator for every calculation
      *  loop
      *  @required
      **/
     calculate:function()
     {
     },
 
 
     /**
      *  Called if the simulation mode is starting
      *  @required
      **/
     onStart:function()
     {
     },
 
     /**
      *  Called if the simulation mode is stopping
      *  @required
      **/
     onStop:function()
     {
     }
 });