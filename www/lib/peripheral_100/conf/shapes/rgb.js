// Generated Code for the Draw2D touch HTML5 lib
//                                                        
// http://www.draw2d.org                                  
//                                                        
// Go to the Designer http://www.draw2d.org               
// to design your own shape or download user generated    
//                                                        
var rgb = draw2d.SetFigure.extend({            

    NAME: "rgb",
 
    init:function(attr, setter, getter)
    {
      this._super( $.extend({stroke:0, bgColor:null, width:766,height:200},attr), setter, getter);
      var port;
      // Port
      port = this.createPort("hybrid", new draw2d.layout.locator.XYRelPortLocator(49.828645392464274, 69.5));
      port.setConnectionDirection();
      port.setBackgroundColor("#D3DB5C");
      port.setName("Port");
      port.setMaxFanOut(20);
      // Port
      port = this.createPort("hybrid", new draw2d.layout.locator.XYRelPortLocator(49.828645392464274, 57));
      port.setConnectionDirection();
      port.setBackgroundColor("#1D4FF3");
      port.setName("Port");
      port.setMaxFanOut(20);
      // Port
      port = this.createPort("hybrid", new draw2d.layout.locator.XYRelPortLocator(49.828645392464274, 43.25));
      port.setConnectionDirection();
      port.setBackgroundColor("#F30000");
      port.setName("Port");
      port.setMaxFanOut(20);
      // Port
      port = this.createPort("hybrid", new draw2d.layout.locator.XYRelPortLocator(49.828645392464274, 28.75));
      port.setConnectionDirection();
      port.setBackgroundColor("#35F320");
      port.setName("Port");
      port.setMaxFanOut(20);
      // Port
      port = this.createPort("hybrid", new draw2d.layout.locator.XYRelPortLocator(71.16288850906268, 57.5));
      port.setConnectionDirection();
      port.setBackgroundColor("#D3DB5C");
      port.setName("Port");
      port.setMaxFanOut(20);
      // Port
      port = this.createPort("hybrid", new draw2d.layout.locator.XYRelPortLocator(71.16288850906268, 51.5));
      port.setConnectionDirection();
      port.setBackgroundColor("#1D4FF3");
      port.setName("Port");
      port.setMaxFanOut(20);
      // Port
      port = this.createPort("hybrid", new draw2d.layout.locator.XYRelPortLocator(71.16288850906268, 45.5));
      port.setConnectionDirection();
      port.setBackgroundColor("#F30000");
      port.setName("Port");
      port.setMaxFanOut(20);
      // Port
      port = this.createPort("hybrid", new draw2d.layout.locator.XYRelPortLocator(71.16288850906268, 39));
      port.setConnectionDirection();
      port.setBackgroundColor("#35F320");
      port.setName("Port");
      port.setMaxFanOut(1);
      // Port
      port = this.createPort("output", new draw2d.layout.locator.XYRelPortLocator(14.95190698935695, 29.8828405064192));
      port.setConnectionDirection();
      port.setBackgroundColor("#37B1DE");
      port.setName("RED");
      port.setMaxFanOut(1);
      // Port
      port = this.createPort("output", new draw2d.layout.locator.XYRelPortLocator(14.95190698935695, 39));
      port.setConnectionDirection();
      port.setBackgroundColor("#37B1DE");
      port.setName("BLUE");
      port.setMaxFanOut(1);
      // Port
      port = this.createPort("output", new draw2d.layout.locator.XYRelPortLocator(14.95190698935695, 47.8671875));
      port.setConnectionDirection();
      port.setBackgroundColor("#37B1DE");
      port.setName("GREEN");
      port.setMaxFanOut(1);
      this.persistPorts=false;
    },
 
    createShapeElement : function()
    {
       var shape = this._super();
       this.originalWidth = 766.373567163459;
       this.originalHeight= 200;
       return shape;
    },
 
    createSet: function()
    {
        this.canvas.paper.setStart();
 
         // BoundingBox
         shape = this.canvas.paper.path("M0,0 L766.373567163459,0 L766.373567163459,200 L0,200");
         shape.attr({"stroke":"none","stroke-width":0,"fill":"none"});
         shape.data("name","BoundingBox");
         
         // Rectangle
         shape = this.canvas.paper.path('M402.5874629532973,190Q402.5874629532973,200 392.5874629532973, 200L119.5874629532974,200Q109.5874629532974,200 109.5874629532974, 190L109.5874629532974,10Q109.5874629532974,0 119.5874629532974, 0L392.5874629532973,0Q402.5874629532973,0 402.5874629532973, 10L402.5874629532973,190');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#FF352E","dasharray":null,"opacity":0.9});
         shape.data("name","Rectangle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":8,"ry":8,"cx":390.37356716345903,"cy":16,"stroke":"none","stroke-width":0,"fill":"#FFFFFF","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":8,"ry":8,"cx":390.37356716345903,"cy":185,"stroke":"none","stroke-width":0,"fill":"#FFFFFF","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":8,"ry":8,"cx":123.37356716345903,"cy":16,"stroke":"none","stroke-width":0,"fill":"#FFFFFF","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":8,"ry":8,"cx":123.37356716345903,"cy":185,"stroke":"none","stroke-width":0,"fill":"#FFFFFF","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Rectangle
         shape = this.canvas.paper.path('M397.37356716345903 152L366.37356716345903 152L366.37356716345903 44L397.37356716345903 44Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#2CAD2C","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Label
         shape = this.canvas.paper.text(0,0,'1');
         shape.attr({"x":347.37356716345903,"y":140.5,"text-anchor":"start","text":"1","font-family":"\"Arial\"","font-size":16,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'2');
         shape.attr({"x":347.37356716345903,"y":115.5,"text-anchor":"start","text":"2","font-family":"\"Arial\"","font-size":16,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'3');
         shape.attr({"x":347.37356716345903,"y":86.5,"text-anchor":"start","text":"3","font-family":"\"Arial\"","font-size":16,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'4');
         shape.attr({"x":347.37356716345903,"y":57.5,"text-anchor":"start","text":"4","font-family":"\"Arial\"","font-size":16,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Rectangle
         shape = this.canvas.paper.path('M766.373567163459 123L529.373567163459 123L529.373567163459 67L766.373567163459 67Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#FFFFFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M603.373567163459 77L641.373567163459 77L641.373567163459 115L603.373567163459 115Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#FFFFFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M660.373567163459 114L698.373567163459 114L698.373567163459 76L660.373567163459 76Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#FFFFFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M750.373567163459 114L712.373567163459 114L712.373567163459 76L750.373567163459 76Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#FFFFFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":18.5,"ry":18.5,"cx":621.873567163459,"cy":95.5,"stroke":"#303030","stroke-width":2,"fill":"#FFFFFF","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":18.5,"ry":18.5,"cx":679.373567163459,"cy":94.5,"stroke":"#303030","stroke-width":2,"fill":"#FFFFFF","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":18,"ry":18,"cx":731.373567163459,"cy":95,"stroke":"#303030","stroke-width":2,"fill":"#FFFFFF","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Label
         shape = this.canvas.paper.text(0,0,'+V');
         shape.attr({"x":555.373567163459,"y":114.5,"text-anchor":"start","text":"+V","font-family":"\"Arial\"","font-size":13,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'B');
         shape.attr({"x":563.373567163459,"y":103.1953125,"text-anchor":"start","text":"B","font-family":"\"Arial\"","font-size":13,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'R');
         shape.attr({"x":562.373567163459,"y":90.3671875,"text-anchor":"start","text":"R","font-family":"\"Arial\"","font-size":13,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'G');
         shape.attr({"x":561.373567163459,"y":77.3671875,"text-anchor":"start","text":"G","font-family":"\"Arial\"","font-size":13,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Rectangle
         shape = this.canvas.paper.path('M302.9997733952158 66.1406810128384L258.9997733952158 66.1406810128384L258.9997733952158 26.1406810128384L302.9997733952158 26.1406810128384Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#303030","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Label
         shape = this.canvas.paper.text(0,0,'T1');
         shape.attr({"x":272.37356716345903,"y":18.08651260945919,"text-anchor":"start","text":"T1","font-family":"\"Arial\"","font-size":16,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Rectangle
         shape = this.canvas.paper.path('M259.67460752001614 89.1406810128384L303.5235392576161 89.1406810128384L303.5235392576161 129.1406810128384L259.67460752001614 129.1406810128384Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#303030","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M258.9997733952158 149.2306181136385L302.9997733952158 149.2306181136385L302.9997733952158 189.2306181136385L258.9997733952158 189.2306181136385Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#303030","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Label
         shape = this.canvas.paper.text(0,0,'T3');
         shape.attr({"x":271.9997733952158,"y":142.0156810128384,"text-anchor":"start","text":"T3","font-family":"\"Arial\"","font-size":16,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'T2');
         shape.attr({"x":271.9997733952158,"y":81.0156810128384,"text-anchor":"start","text":"T2","font-family":"\"Arial\"","font-size":16,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'+');
         shape.attr({"x":376.37356716345903,"y":161.875,"text-anchor":"start","text":"+","font-family":"\"Arial\"","font-size":16,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":17.5,"ry":17.5,"cx":214.11448254464972,"cy":65.5,"stroke":"#1B1B1B","stroke-width":1,"fill":"#AFB5C0","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Rectangle
         shape = this.canvas.paper.path('M115.37356716345903 121L148.37356716345903 121L148.37356716345903 166L115.37356716345903 166Z');
         shape.attr({"stroke":"none","stroke-width":0,"fill":"#2CAD2C","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5.610807836540971,"ry":5.610807836540971,"cx":130.984375,"cy":154.84142595017948,"stroke":"none","stroke-width":0,"fill":"#95C06A","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Label
         shape = this.canvas.paper.text(0,0,'2');
         shape.attr({"x":150.37356716345903,"y":155.8125,"text-anchor":"start","text":"2","font-family":"\"Arial\"","font-size":16,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'1');
         shape.attr({"x":150.37356716345903,"y":132.8125,"text-anchor":"start","text":"1","font-family":"\"Arial\"","font-size":16,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'+');
         shape.attr({"x":130.37356716345903,"y":174.8125,"text-anchor":"start","text":"+","font-family":"\"Arial\"","font-size":16,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'+12V');
         shape.attr({"x":41.13651172389018,"y":155.8125,"text-anchor":"start","text":"+12V","font-family":"\"Arial\"","font-size":16,"stroke":"none","fill":"#FF0000","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":5.5,"ry":5.5,"cx":131.21842584847332,"cy":134.03879282640503,"stroke":"none","stroke-width":0,"fill":"#95C06A","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Rectangle
         shape = this.canvas.paper.path('M73.37356716345903 123L78.984375 123L78.984375 145.8440033344881L73.37356716345903 145.8440033344881Z');
         shape.attr({"stroke":"#000000","stroke-width":1,"fill":"#000000","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M68.99953964652309 124.93184493148584L63.99953964652309 124.93184493148584L63.99953964652309 142.93184493148584L68.99953964652309 142.93184493148584Z');
         shape.attr({"stroke":"#000000","stroke-width":1,"fill":"#000000","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M60.37356716345903 139L55.37356716345903 139L55.37356716345903 128L60.37356716345903 128Z');
         shape.attr({"stroke":"#000000","stroke-width":1,"fill":"#000000","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('MNaN NaNLNaN NaNLNaN NaNLNaN NaNZ');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#FFFFFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Label
         shape = this.canvas.paper.text(0,0,'GND 12V');
         shape.attr({"x":5,"y":115.8125,"text-anchor":"start","text":"GND 12V","font-family":"\"Arial\"","font-size":16,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'GND');
         shape.attr({"x":122.37356716345903,"y":39.9531810128384,"text-anchor":"start","text":"GND","font-family":"\"Arial\"","font-size":16,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'RED');
         shape.attr({"x":122.37356716345903,"y":58.8125,"text-anchor":"start","text":"RED","font-family":"\"Arial\"","font-size":16,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'BLUE');
         shape.attr({"x":122.37356716345903,"y":77.3125,"text-anchor":"start","text":"BLUE","font-family":"\"Arial\"","font-size":16,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Label
         shape = this.canvas.paper.text(0,0,'GREEN');
         shape.attr({"x":122.37356716345903,"y":95.3125,"text-anchor":"start","text":"GREEN","font-family":"\"Arial\"","font-size":16,"stroke":"none","fill":"#080808","stroke-scale":true,"font-weight":"normal","stroke-width":0,"opacity":1});
         shape.data("name","Label");
         
         // Circle
         shape = this.canvas.paper.ellipse();
         shape.attr({"rx":3.6069478949192444,"ry":3.6069478949192444,"cx":115.69912614036446,"cy":39.1666394300745,"stroke":"none","stroke-width":0,"fill":"#000000","dasharray":null,"opacity":1});
         shape.data("name","Circle");
         
         // Rectangle
         shape = this.canvas.paper.path('M76.02269929625368 29L81.02269929625368 29L81.02269929625368 50.24091538119063L76.02269929625368 50.24091538119063Z');
         shape.attr({"stroke":"#000000","stroke-width":1,"fill":"#000000","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M67.60648754144222 31.5519716519118L72.60648754144222 31.5519716519118L72.60648754144222 47.5828511848859L67.60648754144222 47.5828511848859Z');
         shape.attr({"stroke":"#000000","stroke-width":1,"fill":"#000000","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M259.8167331418017 31.247384940785366L247.71021291849968 31.247384940785366L247.71021291849968 36.247384940785366L259.8167331418017 36.247384940785366Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#303030","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M259.37356716345903 56L247.37356716345903 56L247.37356716345903 61L259.37356716345903 61Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#303030","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M259.07126591718384 43L254.07126591718384 43L254.07126591718384 48L259.07126591718384 48Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#303030","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M247.37356716345903 95L259.71286088746126 95L259.71286088746126 100.10591464441472L247.37356716345903 100.10591464441472Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#303030","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M247.37356716345903 120L259.37356716345903 120L259.37356716345903 125L247.37356716345903 125Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#303030","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M254.37356716345903 108L259.37356716345903 108L259.37356716345903 113L254.37356716345903 113Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#303030","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M250.8820391389014 NaNL263.22133286290364 NaNL263.22133286290364 NaNL250.8820391389014 NaNZ');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#FFFFFF","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M247.37356716345903 154.32415732724752L259.37356716345903 154.32415732724752L259.37356716345903 159.32415732724752L247.37356716345903 159.32415732724752Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#303030","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M247.37356716345903 178L259.37356716345903 178L259.37356716345903 183L247.37356716345903 183Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#303030","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Rectangle
         shape = this.canvas.paper.path('M254.28598223517793 171.6634510512498L259.2859822351779 171.6634510512498L259.2859822351779 166.6634510512498L254.28598223517793 166.6634510512498Z');
         shape.attr({"stroke":"#303030","stroke-width":1,"fill":"#303030","dasharray":null,"opacity":1});
         shape.data("name","Rectangle");
         
         // Line_shadow
         shape = this.canvas.paper.path('M381.5 139.5L542.5,115.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":3,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M381.5 139.5L542.5,115.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"#D3DB5C","stroke-width":3,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line");
         
         // Line_shadow
         shape = this.canvas.paper.path('M381.5 114.5L543.5,102.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":3,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M381.5 114.5L543.5,102.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"#1D4FF3","stroke-width":3,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line");
         
         // Line_shadow
         shape = this.canvas.paper.path('M384.5 87.5L540.5,90.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":3,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M384.5 87.5L540.5,90.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"#FF0000","stroke-width":3,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line");
         
         // Line_shadow
         shape = this.canvas.paper.path('M383.5 56.5L462.5,66.5L541.5,76.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":3,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M383.5 56.5L462.5,66.5L541.5,76.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"#35F320","stroke-width":3,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line");
         
         // Line_shadow
         shape = this.canvas.paper.path('M193.5 46.5L234.5,46.5L234.5,77.5L226.5,86.5L202.5,86.5L193.5,76.5L193.5,46.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":1,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M193.5 46.5L234.5,46.5L234.5,77.5L226.5,86.5L202.5,86.5L193.5,76.5L193.5,46.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"#000000","stroke-width":1,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line");
         
         // Line_shadow
         shape = this.canvas.paper.path('M130.5 154.5L108.5,154.5L85.5,154.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":3,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M130.5 154.5L108.5,154.5L85.5,154.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"#FF0000","stroke-width":3,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line");
         
         // Line_shadow
         shape = this.canvas.paper.path('M130.5 134.5L104.5,134.5L78.5,134.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":3,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M130.5 134.5L104.5,134.5L78.5,134.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"#000000","stroke-width":3,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line");
         
         // Line_shadow
         shape = this.canvas.paper.path('M116.5 39.5L77.5,39.5');
         shape.attr({"stroke-linecap":"round","stroke-linejoin":"round","stroke":"none","stroke-width":3,"stroke-dasharray":null,"opacity":1});
         shape.data("name","Line_shadow");
         
         // Line
         shape = this.canvas.paper.path('M116.5 39.5L77.5,39.5');
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
 rgb = rgb.extend({
 
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