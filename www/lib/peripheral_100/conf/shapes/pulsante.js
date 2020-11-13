// Generated Code for the Draw2D touch HTML5 lib
//                                                        
// http://www.draw2d.org                                  
//                                                        
// Go to the Designer http://www.draw2d.org               
// to design your own shape or download user generated    
//                                                        
var pulsante = draw2d.SetFigure.extend({            

    NAME: "pulsante",
 
    init:function(attr, setter, getter)
    {
      this._super( $.extend({stroke:0, bgColor:null, width:60,height:100},attr), setter, getter);
      var port;
      // Port
      port = this.createPort("output", new draw2d.layout.locator.XYRelPortLocator(81.62782843633909, 87.59689922480621));
      port.setConnectionDirection();
      port.setBackgroundColor("#37B1DE");
      port.setName("Port");
      port.setMaxFanOut(1);
      this.persistPorts=false;
    },
 
    createShapeElement : function()
    {
       var shape = this._super();
       this.originalWidth = 92;
       this.originalHeight= 129;
       return shape;
    },
 
    createSet: function()
    {
        this.canvas.paper.setStart();
 
         // BoundingBox
         shape = this.canvas.paper.path("M0,0 L92.53125,0 L92.53125,129 L0,129");
         shape.attr({"stroke":"none","stroke-width":0,"fill":"none"});
         shape.data("name","BoundingBox");
         
         // Rectangle
         shape = this.canvas.paper.path('M46.53125 7L76.53125 7L76.53125 42L46.53125 42Z');
         shape.attr({"fill":"0-#830000-#ff0000","stroke":"#303030","stroke-width":1,"dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M36.53125,3Q36.53125,0 39.53125, 0L82.53125,0Q85.53125,0 85.53125, 3L85.53125,23Q85.53125,26 82.53125, 26L39.53125,26Q36.53125,26 36.53125, 23L36.53125,3');
         shape.attr({"fill":"0-#820000-#ff0000","stroke":"none","stroke-width":0,"dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M29.53125 40L92.53125 40L92.53125 75L29.53125 75Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#707070","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M31.53125 110L57.131249999999966 110L57.131249999999966 115L31.53125 115Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#303030","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M34.53125 117L53.66776199999998 117L53.66776199999998 122L34.53125 122Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#303030","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M37.53125 124L50.53125 124L50.53125 129L37.53125 129Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#303030","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Label
         shape = this.canvas.paper.text(0,0,'GND');
         shape.attr({"x":5,"y":89.90625,"text-anchor":"start","text":"GND","font-family":"\"Arial\"","font-size":16,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Line_shadow
         shape = this.canvas.paper.path('M74.5 76.5L74.5,74.5L74.5,92.5L74.5,111.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":2,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M74.5 76.5L74.5,74.5L74.5,92.5L74.5,111.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"#000000","stroke-width":2,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line");
         
         // Line_shadow
         shape = this.canvas.paper.path('M45.5 74.5L45.5,92.5L45.5,110.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":2,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M45.5 74.5L45.5,92.5L45.5,110.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"#000000","stroke-width":2,"stroke-dasharray":null,"opacity":1});
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
 pulsante = pulsante.extend({
 
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