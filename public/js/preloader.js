window.addEventListener("load",(function(){document.querySelector("#mask").remove(),document.querySelector("#main").hidden=!1})),function(e){var n={animateVideo:function(){var e=this,n=document.getElementById("video"),t=document.getElementById("canvas"),d=t.getContext("2d");t.width=250,t.height=250,n.addEventListener("play",(function(){e.draw(this,d,250,250)}),!1),n.play()},draw:function(e,n,t,d){var i=this;if(e.paused||e.ended)return!1;n.drawImage(e,0,0,t,d),setTimeout((function(){i.draw(e,n,t,d)}),60)}};e.Animation=n,n.animateVideo()}(window);