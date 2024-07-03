(() => {

    window.addEventListener("load", (function () {
            document.querySelector("#mask").remove();
            document.querySelector("#main").hidden = !1;
        }
    ));

})();


;(function(window){

    var Animation = {

        animateVideo: function () {
            var self = this,
                video = document.getElementById('video'),
                canvas = document.getElementById('canvas'),
                context = canvas.getContext('2d'),
                // width = canvas.clientWidth,
                // height = canvas.clientHeight;
                width = 250,
                height = 250;

            canvas.width = width;
            canvas.height = height;

            video.addEventListener('play', function() {
                self.draw(this, context, width, height);
            }, false);

            video.play();
        },

        draw: function (video, context, width, height) {
            var self = this;

            if(video.paused || video.ended) return false;
            context.drawImage(video,0,0,width,height);

            setTimeout(function() {
                self.draw(video, context, width, height);
            }, 60);

        }

    }

    window.Animation = Animation;

    Animation.animateVideo();

}(window));

