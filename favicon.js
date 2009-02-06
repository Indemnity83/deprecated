function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      oldonload();
      func();
    }
  }
}

function faviconizeFavilist() { 
  if (!document.getElementsByTagName) return false;
  if (!document.createElement) return false;
  var ul = document.getElementsByTagName("ul");
  for (var i=0; i<ul.length; i++) {
  	if (ul[i].className == "favilist") {
  		var links = ul[i].getElementsByTagName("a");
  		for (var j=0; j<links.length; j++) {
  			var hoststring = /^http:/;
  			var hrefvalue = links[j].getAttribute("href",2);
			if (hrefvalue.search(hoststring) != -1) {
				var domain = hrefvalue.match(/(\w+):\/\/([^/:]+)(:\d*)?([^# ]*)/);
				domain = RegExp.$2;
				var cue = document.createElement("img");
				cue.className = "faviconimg";
				var cuesrc = "http://"+domain+"/favicon.ico";
				cue.setAttribute("src",cuesrc);
				cue.onerror = function () {
					this.src = "/wp-content/themes/k2/styles/evincere/images/external.gif";
					}
				links[j].parentNode.insertBefore(cue,links[j]);
			}
		}
  	}
  }
}
addLoadEvent(faviconizeFavilist);