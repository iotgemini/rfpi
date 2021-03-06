// Generated Code for the Draw2D touch HTML5 lib
//                                                        
// http://www.draw2d.org                                  
//                                                        
// Go to the Designer http://www.draw2d.org               
// to design your own shape or download user generated    
//                                                        
var tempSensor = draw2d.SetFigure.extend({            

    NAME: "tempSensor",
 
    init:function(attr, setter, getter)
    {
      this._super( $.extend({stroke:0, bgColor:null, width:100,height:60},attr), setter, getter);
      var port;
      // Port
      port = this.createPort("output", new draw2d.layout.locator.XYRelPortLocator(60.954247565382495, 83.16831683168317));
      port.setConnectionDirection();
      port.setBackgroundColor("#37B1DE");
      port.setName("Port");
      port.setMaxFanOut(1);
      this.persistPorts=false;
    },
 
    createShapeElement : function()
    {
       var shape = this._super();
       this.originalWidth = 175;
       this.originalHeight= 101;
       return shape;
    },
 
    createSet: function()
    {
        this.canvas.paper.setStart();
 
         // BoundingBox
         shape = this.canvas.paper.path("M0,0 L175.51524999999998,0 L175.51524999999998,101 L0,101");
         shape.attr({"stroke":"none","stroke-width":0,"fill":"none"});
         shape.data("name","BoundingBox");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":20.5,"ry":14.400000000000034,"cx":108.48399999999998,"cy":14.400000000000034,"stroke":"none","stroke-width":0,"fill":"#545454","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Rectangle
         shape = this.canvas.paper.path('M88.18399999999997 12.199999999999989L128.98399999999998 12.199999999999989L128.98399999999998 44.19999999999999L88.18399999999997 44.19999999999999Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#545454","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M119.19199999999995 82.184L139.92799999999994 82.184L139.92799999999994 87.56L119.19199999999995 87.56Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#303030","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M121.98399999999998 89L136.98399999999998 89L136.98399999999998 94L121.98399999999998 94Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#303030","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M124.98399999999998 96L132.98399999999998 96L132.98399999999998 101L124.98399999999998 101Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#303030","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Label
         shape = this.canvas.paper.text(0,0,'GND');
         shape.attr({"x":134.98399999999998,"y":67.355875,"text-anchor":"start","text":"GND","font-family":"\"Arial\"","font-size":16,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'+');
         shape.attr({"x":67.394,"y":67.355875,"text-anchor":"start","text":"+","font-family":"\"Arial\"","font-size":18,"stroke":"none","fill":"#FF0000","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":3.5,"ry":3.5,"cx":85.06,"cy":82.61200000000002,"stroke":"none","stroke-width":0,"fill":"#000000","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Label
         shape = this.canvas.paper.text(0,0,'+5V');
         shape.attr({"x":5,"y":82.61200000000002,"text-anchor":"start","text":"+5V","font-family":"\"Arial\"","font-size":16,"stroke":"none","fill":"#FF0000","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Line_shadow
         shape = this.canvas.paper.path('M107.5 43.5L107.5,62.5L107.5,82.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":2,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M107.5 43.5L107.5,62.5L107.5,82.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"#000000","stroke-width":2,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line");
         
         // Line_shadow
         shape = this.canvas.paper.path('M121.5 43.5L121.5,59.5L128.5,82.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":2,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M121.5 43.5L121.5,59.5L128.5,82.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"#000000","stroke-width":2,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line");
         
         // Line_shadow
         shape = this.canvas.paper.path('M93.5 42.5L93.5,52.5L93.5,62.5L85.5,82.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":2,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M93.5 42.5L93.5,52.5L93.5,62.5L85.5,82.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"#000000","stroke-width":2,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line");
         
         // Line_shadow
         shape = this.canvas.paper.path('M82.5 82.5L37.5,81.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":2,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M82.5 82.5L37.5,81.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"#FF0000","stroke-width":2,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line");
         
 
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
 tempSensor = tempSensor.extend({
 
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