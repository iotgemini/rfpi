
example.Toolbar = Class.extend({
	
	init:function(elementId, view){
		this.html = $("#"+elementId);
		this.view = view;
		
		// register this class as event listener for the canvas
		// CommandStack. This is required to update the state of 
		// the Undo/Redo Buttons.
		//
		view.getCommandStack().addEventListener(this);

		// Register a Selection listener for the state hnadling
		// of the Delete Button
		//
        view.on("select", $.proxy(this.onSelectionChanged,this));
		/*
		// Inject the UNDO Button and the callbacks
		//
		this.undoButton  = $("<button>Undo</button>");
		this.html.append(this.undoButton);
		this.undoButton.button().click($.proxy(function(){
		       this.view.getCommandStack().undo();
		},this)).button( "option", "disabled", true );

		// Inject the REDO Button and the callback
		//
		this.redoButton  = $("<button>Redo</button>");
		this.html.append(this.redoButton);
		this.redoButton.button().click($.proxy(function(){
		    this.view.getCommandStack().redo();
		},this)).button( "option", "disabled", true );
		
		this.delimiter  = $("<span class='toolbar_delimiter'>&nbsp;</span>");
		this.html.append(this.delimiter);
		*/
		// Inject the DELETE Button
		//
		this.deleteButton  = $("<button>Delete</button>");
		this.html.append(this.deleteButton);
		this.deleteButton.button().click($.proxy(function(){
			var node = this.view.getPrimarySelection();
			var command= new draw2d.command.CommandDelete(node);
			this.view.getCommandStack().execute(command);
		},this)).button( "option", "disabled", true );

		this.delimiter  = $("<span class='toolbar_delimiter'>&nbsp;</span>");
		this.html.append(this.delimiter);

		// Inject the DOWNLOAD Button
		//data-obj='{\"obj-1\": \"some text\",\"obj-2\": \"text-2\",\"obj-3\": \"text-3\"}'
		this.downloadButton  = $("<button class='DownloadJson'>Download JSON</button>");
		this.html.append(this.downloadButton);
		this.downloadButton.button().click(function() {
			var r = confirm("Attenzione salvando il JSON si perde l'attuale schermata!\nPrima se vuoi salvati uno ScreenShot del progetto.");
			if (r == true) {
			$("<a />", {
			  "download": "data.json",
			  "target": "_new",
			  "href" : "data:application/json," + encodeURIComponent(JSON.stringify($(this).data().obj))
			}).appendTo("body")
			.click(function() {
			   $(this).remove()
			})[0].click()
			//location.reload(true);
			window.location = window.location.href.split("?")[0];
			}
			else
			return false;
		  }).button( "option", "", true );	  

		// Inject the PRINT Button
		//
		this.printButton  = $("<button>ScreenShot</button>");
		this.html.append(this.printButton);
		this.printButton.button().click(function() {
			//window.print();
			//return false;
			printJS('canvas', 'html');
		  }).button( "option", "", true );

		  this.delimiter  = $("<span class='toolbar_delimiter'>&nbsp;</span>");
		  this.html.append(this.delimiter);			  
		  
		// Inject the ZOON IN Button and the callbacks
		//
		this.zoomInButton  = $("<button>Zoom In</button>");
		this.html.append(this.zoomInButton);
		this.zoomInButton.button().click($.proxy(function(){
		      this.view.setZoom(this.view.getZoom()*0.7,true);
		      this.app.layout();
		},this));

		// Inject the 1:1 Button
		//
		this.resetButton  = $("<button>1:1</button>");
		this.html.append(this.resetButton);
		this.resetButton.button().click($.proxy(function(){
		    this.view.setZoom(1.0, true);
            this.app.layout();
		},this));
		
		// Inject the ZOON OUT Button and the callback
		//
		this.zoomOutButton  = $("<button>Zoom Out</button>");
		this.html.append(this.zoomOutButton);
		this.zoomOutButton.button().click($.proxy(function(){
            this.view.setZoom(this.view.getZoom()*1.3, true);
            this.app.layout();
		},this));

		this.delimiter  = $("<span class='toolbar_delimiter'>&nbsp;</span>");
		this.html.append(this.delimiter);	
		
		// Inject the EBAY Button
		//
		this.downloadButton  = $("<button class='buy'>Buy IoT Gemini</button>");
		this.html.append(this.downloadButton);
		this.downloadButton.button().click(function() {
			$("<a />", {
			  "target": "_new",
			  "href" : "https://www.ebay.it/sch/i.html?_from=R40&_trksid=m570.l1313&_nkw=IOTGEMINI&_sacat=0"
			}).appendTo("body")
			.click(function() {})[0].click()

		  }).button( "option", "", true );		
	},

	/**
	 * @method
	 * Called if the selection in the cnavas has been changed. You must register this
	 * class on the canvas to receive this event.
	 *
     * @param {draw2d.Canvas} emitter
     * @param {Object} event
     * @param {draw2d.Figure} event.figure
	 */
	onSelectionChanged : function(emitter, event){
		this.deleteButton.button( "option", "disabled", event.figure===null );
	},
	
	/**
	 * @method
	 * Sent when an event occurs on the command stack. draw2d.command.CommandStackEvent.getDetail() 
	 * can be used to identify the type of event which has occurred.
	 * 
	 * @template
	 * 
	 * @param {draw2d.command.CommandStackEvent} event
	 **/
	stackChanged:function(event)
	{
		//this.undoButton.button( "option", "disabled", !event.getStack().canUndo() );
		//this.redoButton.button( "option", "disabled", !event.getStack().canRedo() );
	}
});