// Generated Code for the Draw2D touch HTML5 lib
//                                                        
// http://www.draw2d.org                                  
//                                                        
// Go to the Designer http://www.draw2d.org               
// to design your own shape or download user generated    
//                                                        
var dht11 = draw2d.SetFigure.extend({            

    NAME: "dht11",
 
    init:function(attr, setter, getter)
    {
      this._super( $.extend({stroke:0, bgColor:null, width:280,height:80},attr), setter, getter);
      var port;
      // Port
      port = this.createPort("output", new draw2d.layout.locator.XYRelPortLocator(9.900127020497623, 51.09374999999999));
      port.setConnectionDirection();
      port.setBackgroundColor("#37B1DE");
      port.setName("Port");
      port.setMaxFanOut(20);
      this.persistPorts=false;
    },
 
    createShapeElement : function()
    {
       var shape = this._super();
       this.originalWidth = 401.51;
       this.originalHeight= 128;
       return shape;
    },
 
    createSet: function()
    {
        this.canvas.paper.setStart();
 
         // BoundingBox
         shape = this.canvas.paper.path("M0,0 L401.51,0 L401.51,128 L0,128");
         shape.attr({"stroke":"none","stroke-width":0,"fill":"none"});
         shape.data("name","BoundingBox");
         
         // Rectangle
         shape = this.canvas.paper.path('M401.51 128L73.5100000000001 128L73.5100000000001 0L401.51 0Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#0F2A42","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M253.99000000000012 1.9200000000000017L399.9100000000002 1.9200000000000017L399.9100000000002 126.08000000000003L253.99000000000012 126.08000000000003Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#3B9EF5","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M392.23000000000013 119.84000000000005L374.95000000000016 119.84000000000005L374.95000000000016 104.48000000000003L392.23000000000013 104.48000000000003Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#66ADFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M392.23000000000013 96.28000000000002L374.95000000000016 96.28000000000002L374.95000000000016 80.92000000000002L392.23000000000013 80.92000000000002Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#66ADFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M392.23000000000013 71.44000000000003L374.95000000000016 71.44000000000003L374.95000000000016 56.08000000000001L392.23000000000013 56.08000000000001Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#66ADFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M392.23000000000013 47.24000000000002L374.95000000000016 47.24000000000002L374.95000000000016 31.88000000000001L392.23000000000013 31.88000000000001Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#66ADFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M392.23000000000013 23.040000000000006L374.95000000000016 23.040000000000006L374.95000000000016 7.68L392.23000000000013 7.68Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#66ADFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M366.5100000000001 23.040000000000006L349.23000000000013 23.040000000000006L349.23000000000013 7.68L366.5100000000001 7.68Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#66ADFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M338.8700000000001 23.040000000000006L321.59000000000015 23.040000000000006L321.59000000000015 7.68L338.8700000000001 7.68Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#66ADFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M366.5100000000001 47.24000000000002L349.23000000000013 47.24000000000002L349.23000000000013 31.88000000000001L366.5100000000001 31.88000000000001Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#66ADFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M338.8700000000001 47.24000000000002L321.59000000000015 47.24000000000002L321.59000000000015 31.88000000000001L338.8700000000001 31.88000000000001Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#66ADFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M366.5100000000001 71.44000000000003L349.23000000000013 71.44000000000003L349.23000000000013 56.08000000000001L366.5100000000001 56.08000000000001Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#66ADFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M338.8700000000001 71.44000000000003L321.59000000000015 71.44000000000003L321.59000000000015 56.08000000000001L338.8700000000001 56.08000000000001Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#66ADFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M366.5100000000001 96.28000000000002L349.23000000000013 96.28000000000002L349.23000000000013 80.92000000000002L366.5100000000001 80.92000000000002Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#66ADFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M338.8700000000001 96.28000000000004L321.59000000000015 96.28000000000004L321.59000000000015 80.92000000000002L338.8700000000001 80.92000000000002Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#66ADFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M366.5100000000001 119.84000000000005L349.23000000000013 119.84000000000005L349.23000000000013 104.48000000000003L366.5100000000001 104.48000000000003Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#66ADFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M338.8700000000001 119.84000000000005L321.59000000000015 119.84000000000005L321.59000000000015 104.48000000000003L338.8700000000001 104.48000000000003Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#66ADFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M254.63 23.39999999999999L227.75 23.39999999999999L227.75 28.39999999999999L254.63 28.39999999999999Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#DEDEDE","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M254.63 48.24000000000002L227.75 48.24000000000002L227.75 53.24000000000002L254.63 53.24000000000002Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#DEDEDE","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M254.63 72.44000000000003L227.75 72.44000000000003L227.75 77.44000000000003L254.63 77.44000000000003Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#DEDEDE","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M254.63 97.28000000000002L227.75 97.28000000000002L227.75 102.28000000000002L254.63 102.28000000000002Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#DEDEDE","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":24,"ry":24,"cx":165.99000000000012,"cy":64.08000000000006,"stroke":"#1B1B1B","stroke-width":5,"fill":"#ffffff","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Label
         shape = this.canvas.paper.text(0,0,'+');
         shape.attr({"x":84.26999999999998,"y":26.437812499999993,"text-anchor":"start","text":"+","font-family":"\"Arial\"","font-size":25,"stroke":"none","fill":"#FFFFFF","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'_');
         shape.attr({"x":84.26999999999998,"y":91.19781250000001,"text-anchor":"start","text":"_","font-family":"\"Arial\"","font-size":25,"stroke":"none","fill":"#FFFFFF","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'+5V');
         shape.attr({"x":5,"y":27.345625000000055,"text-anchor":"start","text":"+5V","font-family":"\"Arial\"","font-size":20,"stroke":"none","fill":"#FF0000","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Rectangle
         shape = this.canvas.paper.path('M40.750000000000114 116.39999999999999L35.750000000000114 116.39999999999999L35.750000000000114 84.39999999999999L40.750000000000114 84.39999999999999Z');
         shape.attr({"stroke":"#000000","stroke-width":1,"fill":"#000000","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M32.75 114.39999999999999L27.75 114.39999999999999L27.75 86.39999999999999L32.75 86.39999999999999Z');
         shape.attr({"stroke":"#000000","stroke-width":1,"fill":"#000000","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M24.75 108.39999999999999L19.75 108.39999999999999L19.75 90.39999999999999L24.75 90.39999999999999Z');
         shape.attr({"stroke":"#000000","stroke-width":1,"fill":"#000000","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Line_shadow
         shape = this.canvas.paper.path('M73.5 25.5L41.5,26.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":3,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M73.5 25.5L41.5,26.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"#000000","stroke-width":3,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line");
         
         // Line_shadow
         shape = this.canvas.paper.path('M73.5 65.5L59.5,65.5L44.5,66.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":3,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M73.5 65.5L59.5,65.5L44.5,66.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"#000000","stroke-width":3,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line");
         
         // Line_shadow
         shape = this.canvas.paper.path('M73.5 99.5L40.5,100.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":3,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M73.5 99.5L40.5,100.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"#000000","stroke-width":3,"stroke-dasharray":null,"opacity":1});
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
 dht11 = dht11.extend({
 
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