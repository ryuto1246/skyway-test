<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Skyway Test</title>
  <script src="https://cdn.webrtc.ecl.ntt.com/skyway-4.4.1.js"></script>
</head>
<body>
  <video id="video-el" width="400px" autoplay muted playsinline></video>
  <p id="peer-id"></p>
  <textarea id="their-id"></textarea>
  <button id="make-call">発信</button>
  <video width="400px" id="their-video" autoplay muted playsinline></video>

  <script>
      let localStream;

      navigator.mediaDevices.getUserMedia({video: true, audio: true})
        .then( stream => {
          //読み込み成功時
          const videoEl = document.getElementById("video-el");
          videoEl.srcObject = stream;
          videoEl.play();
          localStream = stream;
        }).catch( error => {
          //エラーを出力
          console.error("error:", error)
        });
      
      const peer = new Peer({
        key: "a4d53765-8b1d-4896-b8d5-b83111ffd5b6",
        debug: 3, //デバッグ用に全てのログを出力
      });

      peer.on("open", () => {
        document.getElementById("peer-id").textContent = peer.id;
      });

      document.getElementById("make-call").onclick = () => {
        const theirId = document.getElementById("their-id").value;
        const mediaConnection = peer.call(theirId, localStream);
        setEventListener(mediaConnection);
      };

      const setEventListener = mediaConnection => {
        mediaConnection.on("stream", stream => {
          const videoEl = document.getElementById("their-video");
          videoEl.srcObject = stream;
          videoEl.play();
        })
      };

      peer.on("call", mediaConnection => {
        mediaConnection.answer(localStream);
        setEventListener(mediaConnection);
      })
  </script>
</body>
</html>