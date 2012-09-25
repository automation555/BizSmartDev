/*getXPath function from DZone Snippets by earce on Mon Apr 02 15:17:44 -0400 2007 slightly modified for mootools by Justin Maier*/
/*Returns XPath String*/
Element.implement({
	xpath: function(){
		el = this;
		var path = "";
		for(; el && el.nodeType == 1; el = el.getParent()){
			var idx = 1;
			var xname = el.get('tag');
			for(var sib=el.getPrevious();sib;sib=sib.getPrevious()){
				if(sib.nodeType == 1 && sib.get('tag') == xname)idx++;
			}
			if(idx>1)xname+="["+idx+"]";
			path="/"+xname+path;
		}
		return path;
	}
});