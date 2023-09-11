        function init() {
            var url = document.getElementsByTagName('video')[0].src;
            var videoElement = document.querySelector('.videoContainer video');
            var player = dashjs.MediaPlayer().create();

            player.initialize(videoElement, url, true);
            //var controlbar = new ControlBar(player);
            //controlbar.initialize();
        } 

    document.addEventListener('DOMContentLoaded', function () {
        init();
    });


/*
function InitPlayerDash(){
                var url = document.getElementsByTagName('video').src;
                var player = dashjs.MediaPlayer().create();
                player.initialize(document.querySelector("#videoPlayer"), url, false);
            };
*/
   
    // setup the video element and attach it to the Dash player
	/*
    function setupVideo() {
      var url = "http://wams.edgesuite.net/media/MPTExpressionData02/BigBuckBunny_1080p24_IYUV_2ch.ism/manifest(format=mpd-time-csf)";
      var context = new Dash.di.DashContext();
      var player = new MediaPlayer(context);
                      player.startup();
                      player.attachView(document.querySelector("#videoplayer"));
                      player.attachSource(url);
    }
   */