<!doctype html>
<html>
<head>
  <link href="http://vjs.zencdn.net/5.19.1/video-js.css" rel="stylesheet">

  <!-- If you'd like to support IE8 -->
  <script src="http://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
</head>


  <style>
    body {
      font-family: Arial, sans-serif;
    }
    .info {
      background-color: #eee;
      border: thin solid #333;
      border-radius: 3px;
      margin: 0 0 20px;
      padding: 0 5px;
    }
    /*
      We include some minimal custom CSS to make the playlist UI look good
      in this context.
    */
    .player-container {
      background: #1a1a1a;
      overflow: auto;
      width: 934px;
    }
    .video-js {
      float: left;
    }
    .vjs-playlist {
      float: left;
      width: 300px;
    }
  </style>
</head>
<body>
  <div class="info">
    <h1>Video.js Playlist UI - Default Implementation</h1>
    <p>
      You can see the Video.js Playlist UI plugin in action below. Look at the
      source of this page to see how to use it with your videos.
    </p>
    <p>
      In the default configuration, the plugin looks for the first element that
      has the class "vjs-playlist" and uses that as a container for the list.
    </p>
  </div>

  <div class="player-container">
    <video
      id="video"
      class="video-js"
      height="300"
      width="600"
      controls>
      <source src="http://vjs.zencdn.net/v/oceans.mp4" type="video/mp4">
      <source src="http://vjs.zencdn.net/v/oceans.webm" type="video/webm">
    </video>

    <div class="vjs-playlist">
      <!--
        The contents of this element will be filled based on the
        currently loaded playlist
      -->
    </div>
  </div>

  <script src="node_modules/video.js/dist/video.js"></script>
  <script src="node_modules/videojs-playlist/dist/videojs-playlist.js"></script>
  <script src="dist/videojs-playlist-ui.js"></script>
  <link href="//vjs.zencdn.net/5.4.6/video-js.min.css" rel="stylesheet">
<script src="//vjs.zencdn.net/5.4.6/video.min.js"></script>
  <script>
    var player = videojs('video');
    player.playlist([{
      name: 'Disney\'s Oceans 1',
      description: 'Explore the depths of our planet\'s oceans. ' +
        'Experience the stories that connect their world to ours. ' +
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit, ' +
        'sed do eiusmod tempor incididunt ut labore et dolore magna ' +
        'aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco ' +
        'laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure ' +
        'dolor in reprehenderit in voluptate velit esse cillum dolore eu ' +
        'fugiat nulla pariatur. Excepteur sint occaecat cupidatat non ' +
        'proident, sunt in culpa qui officia deserunt mollit anim id est ' +
        'laborum.',
      duration: 45,
      sources: [
        { src: 'http://vjs.zencdn.net/v/oceans.mp4', type: 'video/mp4' },
        { src: 'http://vjs.zencdn.net/v/oceans.webm', type: 'video/webm' },
      ],
      // you can use <picture> syntax to display responsive images
      thumbnail: [
        {
          srcset: 'test/example/oceans.jpg',
          type: 'image/jpeg',
          media: '(min-width: 400px;)'
        },
        {
          src: 'test/example/oceans-low.jpg'
        }
      ]
    },
    {
      name: 'Disney\'s Oceans 2',
      description: 'Explore the depths of our planet\'s oceans. ' +
        'Experience the stories that connect their world to ours. ' +
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit, ' +
        'sed do eiusmod tempor incididunt ut labore et dolore magna ' +
        'aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco ' +
        'laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure ' +
        'dolor in reprehenderit in voluptate velit esse cillum dolore eu ' +
        'fugiat nulla pariatur. Excepteur sint occaecat cupidatat non ' +
        'proident, sunt in culpa qui officia deserunt mollit anim id est ' +
        'laborum.',
      duration: 45,
      sources: [
        { src: 'http://vjs.zencdn.net/v/oceans.mp4?2', type: 'video/mp4' },
        { src: 'http://vjs.zencdn.net/v/oceans.webm?2', type: 'video/webm' },
      ],
      // you can use <picture> syntax to display responsive images
      thumbnail: [
        {
          srcset: 'test/example/oceans.jpg',
          type: 'image/jpeg',
          media: '(min-width: 400px;)'
        },
        {
          src: 'test/example/oceans-low.jpg'
        }
      ]
    },
    {
      name: 'Disney\'s Oceans 3',
      description: 'Explore the depths of our planet\'s oceans. ' +
        'Experience the stories that connect their world to ours. ' +
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit, ' +
        'sed do eiusmod tempor incididunt ut labore et dolore magna ' +
        'aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco ' +
        'laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure ' +
        'dolor in reprehenderit in voluptate velit esse cillum dolore eu ' +
        'fugiat nulla pariatur. Excepteur sint occaecat cupidatat non ' +
        'proident, sunt in culpa qui officia deserunt mollit anim id est ' +
        'laborum.',
      duration: 45,
      sources: [
        { src: 'http://vjs.zencdn.net/v/oceans.mp4?3', type: 'video/mp4' },
        { src: 'http://vjs.zencdn.net/v/oceans.webm?3', type: 'video/webm' },
      ],
      // you can use <picture> syntax to display responsive images
      thumbnail: [
        {
          srcset: 'test/example/oceans.jpg',
          type: 'image/jpeg',
          media: '(min-width: 400px;)'
        },
        {
          src: 'test/example/oceans-low.jpg'
        }
      ]
    }, {
      name: 'Sintel (No Thumbnail)',
      description: 'The film follows a girl named Sintel who is searching for a baby dragon she calls Scales.',
      sources: [
        { src: 'http://media.w3.org/2010/05/sintel/trailer.mp4', type: 'video/mp4' },
        { src: 'http://media.w3.org/2010/05/sintel/trailer.webm', type: 'video/webm' },
        { src: 'http://media.w3.org/2010/05/sintel/trailer.ogv', type: 'video/ogg' }
      ],
      thumbnail: false
    },
    // This is a really underspecified video to demonstrate the
    // behavior when optional parameters are missing. You'll get better
    // results with more video metadata!
    {
      name: 'Invalid Source',
      sources: [{
        src: 'Invalid'
      }]
    }]);
    // Initialize the playlist-ui plugin with no option (i.e. the defaults).
    player.playlistUi();
  </script>
<script src="http://vjs.zencdn.net/5.19.1/video.js"></script>
</body>
</html>