
example.View = draw2d.Canvas.extend({
	
	init:function(id){
		this._super(id);
		
		this.setScrollArea("#"+id);
		
        this.currentDropConnection = null;
        
        transceiver1 = new transceiver({x: 70, y:80, width:422, height:385});
        this.add(transceiver1);
        elements = new Array();
        jsonElements = new Array();
        let searchParams = new URLSearchParams(window.location.search);
        if(searchParams.has('sent') == true){
          let param = searchParams.get('sent')
          //alert(param);
          rgb1 = new rgb({x: 550, y:180, width:766,height:200});
          this.add(rgb1);
        //aggiungo i links  
        figure = rgb1;
          var c = new draw2d.Connection({
            source:figure.getOutputPort(0),
            target:transceiver1.getInputPort(0) //target:transceiver1.getHybridPort(7)
        });
        this.add(c);        
         var c = new draw2d.Connection({
            source:figure.getOutputPort(1),
            target:transceiver1.getInputPort(3) //target:transceiver1.getHybridPort(7)
        });
        this.add(c); 
        var c = new draw2d.Connection({
            source:figure.getOutputPort(2),
            target:transceiver1.getInputPort(6) //target:transceiver1.getHybridPort(7)
        });
        this.add(c);

        elements.push(["rgb", 4, "out", 1, "00172"]);
        jsonElements.push(3,6,9);
        jsonObj1 = {"MODULE":{"ADDRESS":"0001","NAME":"00182","FW_VERSION":1,"LINK_TYPE":"wireless","NUM_CHANNEL":1,"SHIELD_0":{"NAME":"rgb","ID":4,"PINOUT":{"PIN_0":3,"MASK_0":"out","PIN_1":6,"MASK_1":"out","PIN_2":9,"MASK_2":"out"},"MPN":"00172"}}};
        console.log(JSON.stringify(jsonObj1));
        $(".DownloadJson" ).attr( "data-obj", JSON.stringify(jsonObj1, null, 4) );
        }
	},

    /**
     * @method
     * Called if the DragDrop object is moving around.<br>
     * <br>
     * Graphiti use the jQuery draggable/droppable lib. Please inspect
     * http://jqueryui.com/demos/droppable/ for further information.
     * 
     * @param {HTMLElement} droppedDomNode The dragged DOM element.
     * @param {Number} x the x coordinate of the drag
     * @param {Number} y the y coordinate of the drag
     * 
     * @template
     **/
    onDrag:function(droppedDomNode, x, y )
    {
        //this.remove(transceiver1);
    },
    
    getPorts: function(recursive)
    {
      if(typeof recursive === "boolean" && recursive===false){
          var ports = new draw2d.util.ArrayList();
          ports.addAll(this.inputPorts);
          ports.addAll(this.outputPorts);
          ports.addAll(this.hybridPorts);
          return ports;
      }

      if(this.cachedPorts===null ){
          this.cachedPorts = new draw2d.util.ArrayList();
          this.cachedPorts.addAll(this.inputPorts);
          this.cachedPorts.addAll(this.outputPorts);
          this.cachedPorts.addAll(this.hybridPorts);

          var _this = this;
          this.children.each(function(i,e){
              _this.cachedPorts.addAll( e.figure.getPorts());
          });
      }
              
      return this.cachedPorts;
    },

    
    /**
     * @method
     * Called if the user drop the droppedDomNode onto the canvas.<br>
     * <br>
     * Draw2D use the jQuery draggable/droppable lib. Please inspect
     * http://jqueryui.com/demos/droppable/ for further information.
     * 
     * @param {HTMLElement} droppedDomNode The dropped DOM element.
     * @param {Number} x the x coordinate of the drop
     * @param {Number} y the y coordinate of the drop
     * @param {Boolean} shiftKey true if the shift key has been pressed during this event
     * @param {Boolean} ctrlKey true if the ctrl key has been pressed during the event
     * @private
     **/
    onDrop : function(droppedDomNode, x, y, shiftKey, ctrlKey)
    {
        var type = $(droppedDomNode).data("shape");
        var figure = eval("new "+type+"();");
        // create a command for the undo/redo support
        var command = new draw2d.command.CommandAdd(this, figure, x, y);
        this.getCommandStack().execute(command);



     var start = new draw2d.shape.node.Start({x:50, y:450}); //figure;
     
	 
//console.log(end1.getPort("Port3"));
//console.log(JSON.stringify(transceiver1.getHybridPort(1)));
//salert(pdo);
     //this.add( start);
     //if(pdo)

    // if(transceiver1.isVisible())
    // alert("q");
    /*function ReadCookie(n){
       return $.cookie(n);
    }
    var acookie = ReadCookie("t1");
    alert(ReadCookie("t1"));
    if(acookie == null )
    {
        transceiver1   = new transceiver({x: 600, y:100, width:160, height:240});//transceiver1;//.setPosition(transceiver1.getPort("Port6").getX(), transceiver1.getPort("Port6").getY());
        alert("ddd");
        $.cookie('t1', transceiver1.getId());
        alert($.cookie('t1'));
        this.add( transceiver1);
    }*/
function portstatus(){
/*
* ID=1 -> output digitale: rele/led
* ID=2 -> input digitale: switch
* ID=3 -> input analogico: sensore
* ID=4 -> 3 output PWM: shield RGB
*/    
nId = 1;
mask = "out";
targetPort = null;
mpn = "generic"; //Manufacturing Part Number
if(type == "pulsante")
{
 type = "switch";
 nId = 2;
 mask = "in";
 targetPort = null;
 mpn = "generic";
}
if(type == "tempSensor")
{
 type = "temp-sensor";
 nId = 3;
 mask = "in";
 targetPort = [2,4,7];
 mpn = "MCP9701A";
}
if(type == "rgb")
{
 nId = 4;
 mpn = "00172";
}
if(type == "dht11")
{
 nId = 5;
 mask = "in";
 mpn = "DHT11";
}
if(type == "dht22")
{
 nId = 8;
 mask = "in";
 mpn = "DHT22";
}
if(type == "pir")
{
 type = "pir";
 nId = 7;
 mask = "in";
 targetPort = null;
 mpn = "HC-SR505";
}
if(type == "adc05")
{
 type = "adc";
 nId = 6;
 mask = "in";
 targetPort = [2,4,7];
 mpn = "ADC0V5V";
}
    item = type;
    elements.push([item, nId, mask, targetPort, mpn]);
    console.log(elements);

    jsonObj = {
        "MODULE": 
               {
                "ADDRESS":"0001",
                "NAME":"00182",
                "FW_VERSION" : 1,
                "LINK_TYPE": "wireless",
                "NUM_CHANNEL": 1

            }
    }

    var transceiverPorts = transceiver1.getPorts();
    //--console.log(transceiverPorts);
    //ciclo le porte del transeiver
    for(var i = 0; i < transceiverPorts.getSize(); i++){
        //alert("i: "+ i);
        
       /* if(jsonElements.includes(i+3))
        {
            i = i-1;
            alert("cccc"+i);
        }*/

        if(i < elements.length)
        checkTarget(elements[i][3],i);
        if(i == 7)
        {
            nPort = 7;
            //alert("ddd");
        }
        
        //["PIN_"+nPort]
        //alert(type);
        //var s1 = {"NAME":elements[i][0],"ID":elements[i][1],"PINOUT":{"PIN_0":(nPort+3),"MASK_0":elements[i][2]},"MPN":elements[i][4]};

        //jsonObj.MODULE["SHIELD_"+nPort] = s1;
        var connections = transceiverPorts.get(i).getConnections().asArray();
        //----console.log(connections);
        //alert(connections['length']);
        if(connections['length'] == 0)
        {
         //checkTarget(elements[i][3],i);
         //jsonObj.MODULE["SHIELD_"+nPort] = s1;
         jsonElements.push(nPort+3);
         break;
        }
        //var index = jsonElements.findIndex(x => x==nPort+3)
        //if (index === -1)
        // jsonElements.push(nPort+3);  //[type,elements[i][1],nPort+3,elements[i][2]]

        
    }
    //ordina gli index pin collegati dal più piccolo al più grande
    //jsonElements = jsonElements.sort(function(a, b){return a-b});
    console.log(jsonElements);
//alert(transceiverPorts.get(2).getConnections().asArray()['length']);

    function checkTarget(el,ls){
      if((type == "temp-sensor" && el != null) || (type == "adc" && el != null))
      {
          //alert("qui");
        for(var x = 0; x < el.length; ++x) {  
         //controlla se la prima porta è già occupata
         if(transceiverPorts.get(el[x]).getConnections().asArray()['length'] == 0)
         {
          nPort = el[x];
          //alert(nPort);
          break;
         }
        }
      }
      else
      {
       if(jsonElements.includes(ls+3))
        {
            //alert("QUU");
            nPort = ls+1;
        }
      
        else
        nPort = ls;
      } 
    }
    //alert("nPort: " + nPort);
}

function generateJson(){
    jsonObj1 = {
        "MODULE": 
               {
                "ADDRESS":"0001",
                "NAME":"00182",
                "FW_VERSION" : 1,
                "LINK_TYPE": "wireless",
                "NUM_CHANNEL": 1

            }
    }
    for(var j = 0; j < elements.length; ++j){
        g = j;
        if(elements[j][0] == "rgb")
        {
         var shield = {"NAME":elements[j][0],"ID":elements[j][1],"PINOUT":{"PIN_0":3,"MASK_0":elements[j][2],"PIN_1":6,"MASK_1":elements[j][2],"PIN_2":9,"MASK_2":elements[j][2]},"MPN":elements[j][4]};
        }
        else
        {
          if(jsonElements.filter((m,idx) => idx < 3) == "3,6,9") //controllo se c'è rgb che occupa i pin 3,6,9
           g = j + 2;   
         var shield = {"NAME":elements[j][0],"ID":elements[j][1],"PINOUT":{"PIN_0":(jsonElements[g]),"MASK_0":elements[j][2]},"MPN":elements[j][4]};
        }
         jsonObj1.MODULE["SHIELD_"+j] = shield;
    }
    console.log(JSON.stringify(jsonObj1));
}
     
    //---console.log(transceiver1.getId());
    portstatus();
//var canvas = new draw2d.Canvas("canvas");
/*var c = new draw2d.Connection({
	     source:figure.getOutputPort(0),
         target:transceiver1.getInputPort(nPort) //target:transceiver1.getHybridPort(7)
	 });
     this.add(c);*/
if(type == "rgb")
{  
    var c = new draw2d.Connection({
        source:figure.getOutputPort(0),
        target:transceiver1.getInputPort(0) //target:transceiver1.getHybridPort(7)
    });
    this.add(c);        
     var c = new draw2d.Connection({
        source:figure.getOutputPort(1),
        target:transceiver1.getInputPort(3), //target:transceiver1.getHybridPort(7)
        //rec.transceiver1.setConnectionAnchor(3)
    });
    this.add(c); 
    var c = new draw2d.Connection({
        source:figure.getOutputPort(2),
        target:transceiver1.getInputPort(6) //target:transceiver1.getHybridPort(7)
    });
    this.add(c);
    jsonElements.push(6,9);
}
else
{
    var c = new draw2d.Connection({
        source:figure.getOutputPort(0),
        target:transceiver1.getInputPort(nPort) //target:transceiver1.getHybridPort(7)
    });
    this.add(c);
}
     //var pdo = 1;

    generateJson();
    //console.log(JSON.stringify(jsonObj));
    $(".DownloadJson" ).attr( "data-obj", JSON.stringify(jsonObj1, null, 4) );     
    }
});

/*
{
	"MODULE":{
		"ADDRESS":"0001",
		"NAME":"00182",
		"FW_VERSION":1,
		"LINK_TYPE":"wireless",
		"NUM_CHANNEL":1,
		"SHIELD_0":{
			"NAME":"led",
			"ID":1,
			"PINOUT":{"PIN_0":3,"MASK_0":"out"}
		},
		"SHIELD_1":{
			"NAME":"rele",
			"ID":1,
			"PINOUT":{"PIN_0":4,"MASK_0":"out"}
		},
		"SHIELD_2":{
			"NAME":"switch",
			"ID":2,
			"PINOUT":{"PIN_0":5,"MASK_0":"in"}
		}
	}
}
*/

