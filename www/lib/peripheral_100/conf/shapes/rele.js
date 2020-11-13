// Generated Code for the Draw2D touch HTML5 lib
//                                                        
// http://www.draw2d.org                                  
//                                                        
// Go to the Designer http://www.draw2d.org               
// to design your own shape or download user generated    
//                                                        
var rele = draw2d.SetFigure.extend({            

    NAME: "rele",
 
    init:function(attr, setter, getter)
    {
      this._super( $.extend({stroke:0, bgColor:null, width:300,height:165},attr), setter, getter);
      var port;
      // Port
      port = this.createPort("output", new draw2d.layout.locator.XYRelPortLocator(12.899998545414752, 37.35488276804006));
      port.setConnectionDirection();
      port.setBackgroundColor("#37B1DE");
      port.setName("Port");
      port.setMaxFanOut(1);
      this.persistPorts=false;
    },
 
    createShapeElement : function()
    {
       var shape = this._super();
       this.originalWidth = 353;
       this.originalHeight= 205;
       return shape;
    },
 
    createSet: function()
    {
        this.canvas.paper.setStart();
 
         // BoundingBox
         shape = this.canvas.paper.path("M0,0 L353.07659019999994,0 L353.07659019999994,205.921875 L0,205.921875");
         shape.attr({"stroke":"none","stroke-width":0,"fill":"none"});
         shape.data("name","BoundingBox");
         
         // Rectangle
         shape = this.canvas.paper.path('M77.07659019999994 23.921875L353.07659019999994 23.921875L353.07659019999994 205.921875L77.07659019999994 205.921875Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#666666","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":10,"ry":10,"cx":98.07659019999994,"cy":38.921875,"stroke":"none","stroke-width":0,"fill":"#FFFFFF","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":10,"ry":10,"cx":332.07659019999994,"cy":38.921875,"stroke":"none","stroke-width":0,"fill":"#FFFFFF","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":10,"ry":10,"cx":332.07659019999994,"cy":189.921875,"stroke":"none","stroke-width":0,"fill":"#FFFFFF","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":10,"ry":10,"cx":98.07659019999994,"cy":189.921875,"stroke":"none","stroke-width":0,"fill":"#FFFFFF","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Rectangle
         shape = this.canvas.paper.path('M134.52684299999993 54.921875L340.526843 54.921875L340.526843 173.921875L134.52684299999993 173.921875Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#148AFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":9,"ry":9,"cx":326.07659019999994,"cy":80.921875,"stroke":"#1B1B1B","stroke-width":1,"fill":"#C0AF3F","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":9,"ry":9,"cx":326.07659019999994,"cy":114.921875,"stroke":"#1B1B1B","stroke-width":1,"fill":"#C0AF3F","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":9,"ry":9,"cx":326.07659019999994,"cy":146.921875,"stroke":"#1B1B1B","stroke-width":1,"fill":"#C0AF3F","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Rectangle
         shape = this.canvas.paper.path('M299.9514702,41.626003000000026Q299.9514702,37.626003000000026 295.9514702, 37.626003000000026L243.7894222000001,37.626003000000026Q239.7894222000001,37.626003000000026 239.7894222000001, 41.626003000000026L239.7894222000001,46.01230700000002Q239.7894222000001,50.01230700000002 243.7894222000001, 50.01230700000002L295.9514702,50.01230700000002Q299.9514702,50.01230700000002 299.9514702, 46.01230700000002L299.9514702,41.626003000000026');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#303030","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M89.62023180000006 65.11180140000003L108.96645900000004 65.11180140000003L108.96645900000004 134.00324460000004L89.62023180000006 134.00324460000004Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#8F8F8F","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Label
         shape = this.canvas.paper.text(0,0,'IN');
         shape.attr({"x":111.35201679999994,"y":77.42430140000005,"text-anchor":"start","text":"IN","font-family":"\"Arial\"","font-size":15,"stroke":"none","fill":"#95c06a","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'+');
         shape.attr({"x":111.35201679999994,"y":101.62742640000005,"text-anchor":"start","text":"+","font-family":"\"Arial\"","font-size":17,"stroke":"none","fill":"#de3232","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'GND');
         shape.attr({"x":111.35201679999994,"y":124.89305140000005,"text-anchor":"start","text":"GND","font-family":"\"Arial\"","font-size":15,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Rectangle
         shape = this.canvas.paper.path('M100.94485260000005,148.79972460000005Q100.94485260000005,145.79972460000005 103.94485260000005, 145.79972460000005L108.79761420000006,145.79972460000005Q111.79761420000006,145.79972460000005 111.79761420000006, 148.79972460000005L111.79761420000006,161.67409260000008Q111.79761420000006,164.67409260000008 108.79761420000006, 164.67409260000008L103.94485260000005,164.67409260000008Q100.94485260000005,164.67409260000008 100.94485260000005, 161.67409260000008L100.94485260000005,148.79972460000005');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#0A7878","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":3.774873600000035,"ry":3.774873600000035,"cx":44.321748600000035,"cy":121.26304620000005,"stroke":"none","stroke-width":0,"fill":"#000000","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Rectangle
         shape = this.canvas.paper.path('M28.750395000000026 150.51831660000005L53.750395000000026 150.51831660000005L53.750395000000026 155.51831660000005L28.750395000000026 155.51831660000005Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#303030","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M31.07659019999994 157.921875L51.07659019999994 157.921875L51.07659019999994 162.921875L31.07659019999994 162.921875Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#303030","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('MNaN NaNLNaN NaNLNaN NaNLNaN NaNZ');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#FFFFFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M35.07659019999994 165.921875L47.07659019999994 165.921875L47.07659019999994 170.921875L35.07659019999994 170.921875Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#303030","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":4,"ry":4,"cx":45.821748600000035,"cy":101.921875,"stroke":"none","stroke-width":0,"fill":"#000000","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Label
         shape = this.canvas.paper.text(0,0,'+5V');
         shape.attr({"x":5,"y":13.9609375,"text-anchor":"start","text":"+5V","font-family":"\"Arial\"","font-size":16,"stroke":"none","fill":"#FF0000","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Line_shadow
         shape = this.canvas.paper.path('M329.5 73.5L323.5,89.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":1,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M329.5 73.5L323.5,89.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"#000000","stroke-width":1,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line");
         
         // Line_shadow
         shape = this.canvas.paper.path('M320.5 108.5L332.5,120.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":1,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M320.5 108.5L332.5,120.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"#000000","stroke-width":1,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line");
         
         // Line_shadow
         shape = this.canvas.paper.path('M334.5 142.5L319.5,152.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":1,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M334.5 142.5L319.5,152.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"#000000","stroke-width":1,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line");
         
         // Line_shadow
         shape = this.canvas.paper.path('M299.5 42.5L306.5,42.5L312.5,42.5L312.5,43.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":2,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M299.5 42.5L306.5,42.5L312.5,42.5L312.5,43.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"#2B2B2B","stroke-width":2,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line");
         
         // Line_shadow
         shape = this.canvas.paper.path('M239.5 44.5L229.5,44.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":2,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M239.5 44.5L229.5,44.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"#2B2B2B","stroke-width":2,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line");
         
         // Line_shadow
         shape = this.canvas.paper.path('M99.5 77.5L72.5,77.5L46.5,77.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":2,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M99.5 77.5L72.5,77.5L46.5,77.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"#000000","stroke-width":2,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line");
         
         // Line_shadow
         shape = this.canvas.paper.path('M98.5 100.5L46.5,101.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":2,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M98.5 100.5L46.5,101.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"#000000","stroke-width":2,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line");
         
         // Line_shadow
         shape = this.canvas.paper.path('M98.5 121.5L46.5,121.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":2,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M98.5 121.5L46.5,121.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"#000000","stroke-width":2,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line");
         
         // Line_shadow
         shape = this.canvas.paper.path('M42.5 122.5L42.5,151.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":2,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M42.5 122.5L42.5,151.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"#000000","stroke-width":2,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line");
         
         // Line_shadow
         shape = this.canvas.paper.path('M42.5 101.5L13.5,101.5L12.5,19.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":2,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M42.5 101.5L13.5,101.5L12.5,19.5');
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
 rele = rele.extend({
 
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