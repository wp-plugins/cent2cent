// TinyMCE Editor
(function(b){var e,d,a=[],c=window;b.fn.tinymce=function(j){var p=this,g,k,h,m,i,l="",n="";if(!p.length){return p}if(!j){return tinyMCE.get(p[0].id)}p.css("visibility","hidden");function o(){var r=[],q=0;if(f){f();f=null}p.each(function(t,u){var s,w=u.id,v=j.oninit;if(!w){u.id=w=tinymce.DOM.uniqueId()}s=new tinymce.Editor(w,j);r.push(s);s.onInit.add(function(){var x,y=v;p.css("visibility","");if(v){if(++q==r.length){if(tinymce.is(y,"string")){x=(y.indexOf(".")===-1)?null:tinymce.resolve(y.replace(/\.\w+$/,""));y=tinymce.resolve(y)}y.apply(x||tinymce,r)}}})});b.each(r,function(t,s){s.render()})}if(!c.tinymce&&!d&&(g=j.script_url)){d=1;h=g.substring(0,g.lastIndexOf("/"));if(/_(src|dev)\.js/g.test(g)){n="_src"}m=g.lastIndexOf("?");if(m!=-1){l=g.substring(m+1)}c.tinyMCEPreInit=c.tinyMCEPreInit||{base:h,suffix:n,query:l};if(g.indexOf("gzip")!=-1){i=j.language||"en";g=g+(/\?/.test(g)?"&":"?")+"js=true&core=true&suffix="+escape(n)+"&themes="+escape(j.theme)+"&plugins="+escape(j.plugins)+"&languages="+i;if(!c.tinyMCE_GZ){tinyMCE_GZ={start:function(){tinymce.suffix=n;function q(r){tinymce.ScriptLoader.markDone(tinyMCE.baseURI.toAbsolute(r))}q("langs/"+i+".js");q("themes/"+j.theme+"/editor_template"+n+".js");q("themes/"+j.theme+"/langs/"+i+".js");b.each(j.plugins.split(","),function(s,r){if(r){q("plugins/"+r+"/editor_plugin"+n+".js");q("plugins/"+r+"/langs/"+i+".js")}})},end:function(){}}}}b.ajax({type:"GET",url:g,dataType:"script",cache:true,success:function(){tinymce.dom.Event.domLoaded=1;d=2;if(j.script_loaded){j.script_loaded()}o();b.each(a,function(q,r){r()})}})}else{if(d===1){a.push(o)}else{o()}}return p};b.extend(b.expr[":"],{tinymce:function(g){return g.id&&!!tinyMCE.get(g.id)}});function f(){function i(l){if(l==="remove"){this.each(function(n,o){var m=h(o);if(m){m.remove()}})}this.find("span.mceEditor,div.mceEditor").each(function(n,o){var m=tinyMCE.get(o.id.replace(/_parent$/,""));if(m){m.remove()}})}function k(n){var m=this,l;if(n!==e){i.call(m);m.each(function(p,q){var o;if(o=tinyMCE.get(q.id)){o.setContent(n)}})}else{if(m.length>0){if(l=tinyMCE.get(m[0].id)){return l.getContent()}}}}function h(m){var l=null;(m)&&(m.id)&&(c.tinymce)&&(l=tinyMCE.get(m.id));return l}function g(l){return !!((l)&&(l.length)&&(c.tinymce)&&(l.is(":tinymce")))}var j={};b.each(["text","html","val"],function(n,l){var o=j[l]=b.fn[l],m=(l==="text");b.fn[l]=function(s){var p=this;if(!g(p)){return o.apply(p,arguments)}if(s!==e){k.call(p.filter(":tinymce"),s);o.apply(p.not(":tinymce"),arguments);return p}else{var r="";var q=arguments;(m?p:p.eq(0)).each(function(u,v){var t=h(v);r+=t?(m?t.getContent().replace(/<(?:"[^"]*"|'[^']*'|[^'">])*>/g,""):t.getContent()):o.apply(b(v),q)});return r}}});b.each(["append","prepend"],function(n,m){var o=j[m]=b.fn[m],l=(m==="prepend");b.fn[m]=function(q){var p=this;if(!g(p)){return o.apply(p,arguments)}if(q!==e){p.filter(":tinymce").each(function(s,t){var r=h(t);r&&r.setContent(l?q+r.getContent():r.getContent()+q)});o.apply(p.not(":tinymce"),arguments);return p}}});b.each(["remove","replaceWith","replaceAll","empty"],function(m,l){var n=j[l]=b.fn[l];b.fn[l]=function(){i.call(this,l);return n.apply(this,arguments)}});j.attr=b.fn.attr;b.fn.attr=function(n,q,o){var m=this;if((!n)||(n!=="value")||(!g(m))){return j.attr.call(m,n,q,o)}if(q!==e){k.call(m.filter(":tinymce"),q);j.attr.call(m.not(":tinymce"),n,q,o);return m}else{var p=m[0],l=h(p);return l?l.getContent():j.attr.call(b(p),n,q,o)}}}})(jQuery);

var Cent2Cent = new function () {
	
    // After approval use POST redirect to same page with request id and approval
    this.PostURL = function (strURL,Target) {

        var submitForm = document.createElement("FORM");
        document.body.appendChild(submitForm);
        submitForm.method = "POST";
        submitForm.action = strURL;
		submitForm.target = Target; //"fraCent2Cent";
		
        if (jQuery.browser.msie && jQuery.browser.version < 9) {

            var eleRequestID = document.createElement("<input type=hidden />")
            submitForm.appendChild(eleRequestID);
            eleRequestID.name = this.WEBSITE_PARAM;
            eleRequestID.value = this.WebsiteID;

            var eleApprovalCode = document.createElement("<input type=hidden />");
            submitForm.appendChild(eleApprovalCode);
            eleApprovalCode.name = this.WEBSITE_SECRET_PARAM;
            eleApprovalCode.value = this.Secret;
        }
        else {
            var eleRequestID = document.createElement("input")
            submitForm.appendChild(eleRequestID);
            eleRequestID.type = "hidden";
            eleRequestID.name = this.WEBSITE_PARAM;
            eleRequestID.value = this.WebsiteID;

            var eleApprovalCode = document.createElement("input");
            submitForm.appendChild(eleApprovalCode);
            eleApprovalCode.type = "hidden";
            eleApprovalCode.name = this.WEBSITE_SECRET_PARAM;
            eleApprovalCode.value = this.Secret;
        }

        submitForm.submit();
    };
    
    
	this.ShowLogin = function()
	{
		var strURL = this.SiteURL + "/wp-content/plugins/cent2cent/login.php?popup=true";
		this.ShowOverlay();
		this.PostURL(strURL,"fraCent2Cent");
	}
	
	this.GetContentItemDetails = function()
	{
		jQuery("#pnlCent2Cent").html("Loading...");
		
		var post = escape(this.CurrentPost);
		
		var strURL = this.SiteURL + "/wp-content/plugins/cent2cent/contentitem-ajax.php?url=" + post;
		jQuery.get(strURL, function(data) {
	
			jQuery("#pnlCent2Cent").html(data);
			Cent2Cent.SetSelectionMode();
			
			// Clear if user cancelled and did not create the content item
			if(jQuery("#pnlNoContentItem").length != 0)
			{
				Cent2Cent.ClearPreviousSelection();
			}
		});
	
	};
	
	this.SetStatus = function(itemId,bActivate)
	{
		jQuery("#pnlCent2Cent").html("Loading...");
		var strURL = this.SiteURL + "/wp-content/plugins/cent2cent/contentitem-ajax.php?itemid=" + itemId + "&status=" + bActivate;
		jQuery.get(strURL, function(data) {
	
			Cent2Cent.GetContentItemDetails();
		
		});
	}
	
	this.QuickEdit = function (itemId)
	{
		var strQuickEdit = this.PlatformURL + "/Admin/Wizard/ContentItemEdit.aspx?ContentItemID=" + itemId;
		this.ShowOverlay();
		this.PostURL(strQuickEdit,"fraCent2Cent");	
	};
	
	this.EditItem = function(itemId)
	{
		var strEdit = this.PlatformURL + "/Admin/ContentItem.aspx?ContentItemID=" + itemId;
		this.PostURL(strEdit,"_blank");
		//window.open(strEdit);
	};
	
	this.ViewAdmin = function()
	{
		var strAdmin = this.PlatformURL + "/Admin/";
		this.PostURL(strAdmin,"_blank");
	};
	
	this.NewContentItem = function()
	{
		var post = escape(this.CurrentPost);
		var title = escape(this.PostTitle);

		this.SetTextForProtection();
		
		var strNewItemURL = this.PlatformURL + "/Admin/Wizard/Platforms/WordPress/AddNew.aspx?URL=" + post + "&title=" + title;
		this.ShowOverlay();
		this.PostURL(strNewItemURL,"fraCent2Cent");
	}
	
	this.ShowOverlay = function()
	{
		jQuery("#pnlCent2CentPopUp").show();
		
		if (jQuery.browser.msie && jQuery.browser.version < 8) {
			jQuery("#wpbody").hide();
			jQuery("#fraCent2Cent").css("margin-top","50px");
		}
	};
		
	this.ClosePopUp = function()
	{
		jQuery("#wpbody").show();
		jQuery("#pnlCent2CentPopUp").hide();
		this.GetContentItemDetails();
	}
	
	this.SetTextForProtection = function()
	{
		var editor = jQuery('#content').tinymce();

		// Insert new brackts!
		var selectedText = editor.selection.getContent();
	
		// If something was selected
		if(selectedText)
		{
			// At the beginning			
			var rngStart = editor.selection.getStart();
			jQuery(rngStart).html(this.CENT2CENT_START.replace("]",this.CENT2CENT_NEW) + jQuery(rngStart).html());
		
			// At the end!
			var rngEnd = editor.selection.getEnd();
			jQuery(rngEnd).append(this.CENT2CENT_END.replace("]",this.CENT2CENT_NEW));
			
			// IE on all selection crashes
			try
			{
				var node = editor.selection.getNode();
				editor.selection.select(node,false);
			}catch (e) {}

			//return;
			
			//jNode = jQuery(node);
			//alert(jNode.html());
			//selectedText = this.CENT2CENT_START.replace("]",this.CENT2CENT_NEW) + jNode.html() + this.CENT2CENT_END.replace("]",this.CENT2CENT_NEW);
			//jNode.html(selectedText);
		}
		else
		{
			selectedText = editor.getContent();;
			selectedText = this.CENT2CENT_START.replace("]",this.CENT2CENT_NEW) + selectedText + this.CENT2CENT_END.replace("]",this.CENT2CENT_NEW);
			editor.setContent(selectedText);
		}
		
		this.ClearPreviousSelection();
	};
	

	
	this.ClearPreviousSelection = function()
	{
		var editor = jQuery('#content').tinymce();
			
		// Get current editor data
		var data = editor.getContent();
			
		// Clean previous protection (and remove <p>)
		data = data.replace(new RegExp("(<p>)?" + this.CENT2CENT_START.replace("[","\\[").replace("]","\\]") + "(</p>)?", 'g'), "");
		data = data.replace(new RegExp("(<p>)?" + this.CENT2CENT_END.replace("[","\\[").replace("]","\\]") + "(</p>)?", 'g'), "");
				
		// Replace new ones
		data = data.replace(this.CENT2CENT_START.replace("]",this.CENT2CENT_NEW),this.CENT2CENT_START);
		data = data.replace(this.CENT2CENT_END.replace("]",this.CENT2CENT_NEW),this.CENT2CENT_END);
				
		// Set fixed content
		editor.setContent(data);
		
		this.SetSelectionMode();
	}
	
	
	this.SetSelectionMode = function()
	{
		// Get editor
		var editor = jQuery('#content').tinymce();
	
		// Wait for editor to become ready
		if(typeof(editor) == "undefined")
		{
			setTimeout("Cent2Cent.SetSelectionMode()",300);
			return;
		}
			
		// Get current editor data
		var data = editor.getContent();
		
		var startMatch = new RegExp("(<p>)?" + this.CENT2CENT_START.replace("[","\\[").replace("]","\\]") + "(</p>)?", 'g');
		var idxMatch = data.search(startMatch);
		
		var endMatch = new RegExp("(<p>)?" + this.CENT2CENT_END.replace("[","\\[").replace("]","\\]") + "(</p>)?", 'g');
		var match = data.match(endMatch);
			
		var result;
		
		if(idxMatch == -1 || match == null)
		{
			result = "<font color=red>Nothing is selected!</font>";
		}
		else
		{
			// Calc EOF
			var EOF = data.search(endMatch) +  (match + "").length;
			if(idxMatch == 0 && EOF == data.length)
			{
				result = "All Content";
			}			
			else
			{
				result = "Selection";
			}
		}
		
		jQuery("#pnlSelection").html(result);
	}
	
	this.ShowHideSelectionScreen = function()
	{
		// Do nothing
		return;
		jQuery("#pnlChangeSelection").toggle();
		jQuery("#pnlAll").toggle();
	}
	
	this.PreviousData = null;
	this.SaveDataForCancel = function()
	{
		this.PreviousData = jQuery('#content').tinymce().getContent();
	};
	
	this.RestoreDataAfterCancel = function()
	{
		jQuery('#content').tinymce().setContent(this.PreviousData);
		this.PreviousData = null;
	};
	
	// User decided to cancel the option to reselect the data
	this.CancelSelection = function()
	{
		// Restore to previous state
		this.RestoreDataAfterCancel();
		
		// Hide the selection screen
		this.ShowHideSelectionScreen();
	}
	
	// User decided to confirme the selection
	this.ConfirmSelection = function()
	{
		// Set the text for the protection (add the [cent2cent]...)
		this.SetTextForProtection();
		
		// Hide the page
		this.ShowHideSelectionScreen();
	}
	
	// User want to change the current selection
	this.InitSelectionChange = function()
	{
		// Save previous data (to restore if user cancelled)
		Cent2Cent.SaveDataForCancel();
		
		// Clear all previous selections ([Cent2Cent]) to allow user to simply click continue
		// and get "FULL PAGE" by default
		Cent2Cent.ClearPreviousSelection();
		
		// Hide form
		Cent2Cent.ShowHideSelectionScreen();
	}
	
	this.ShowPopUp = function(strURL,nWidth,nHeight)
	{
	    var nTop = (screen.height - nHeight) / 2;
	    var nLeft = (screen.width - nWidth) / 2;
	    var strAttributes = "width=" + nWidth + "," +
	                                "height=" + nHeight + "," +
	                                "top=" + nTop + "," +
	                                "left=" + nLeft + ",scrollbars=yes";
	
	    try {
	
	        var objWindow = window.open(strURL, "Modal", strAttributes);
	        if (!objWindow) {
	            //alert("You have a popup blocker!");
	        }
	        else {
	            this.wndWallet = objWindow;
	            this.wndWallet.opener = window;
	            objWindow.focus();
	        }
	    }
	    catch (e) {
	    }	
	};
	
};
