

<style>
canvas {
    border:1px solid #d3d3d3;
    background-color: #f1f1f1;
}
</style>

<body onload="startGame()">
    <script>
        var myGamePiece;
        var myObstacles = [];
        var myScore;
        var crashNumber = 0;

        function startGame() {
            myGamePiece = new component(30, 30, "red", 10, 120);
            myGamePiece.gravity = 0.05;
            myScore = new component("30px", "Consolas", "black", 280, 40, "text");
            myGameArea.start();

            document.getElementById("body").addEventListener("touchstart", setGraviti);

        }

        function setGraviti() {
            myGamePiece.gravity = -0.45;
        }

        var myGameArea = {
            canvas : document.createElement("canvas"),
            start : function() {
                this.canvas.width = 900;
                this.canvas.height = 600;
                this.context = this.canvas.getContext("2d");
                document.body.insertBefore(this.canvas, document.body.childNodes[0]);
                this.frameNo = 0;
                this.interval = setInterval(updateGameArea, 20);
                },
            clear : function() {
                this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
            }
        }

        function component(width, height, color, x, y, type) {
            this.type = type;
            this.score = 0;
            this.width = width;
            this.height = height;
            this.speedX = 0;
            this.speedY = 0;    
            this.x = x;
            this.y = y;
            this.gravity = 0;
            this.gravitySpeed = 0;
            this.update = function() {
                ctx = myGameArea.context;
                if (this.type == "text") {
                    ctx.font = this.width + " " + this.height;
                    ctx.fillStyle = color;
                    ctx.fillText(this.text, this.x, this.y);
                } else {
                    ctx.fillStyle = color;
                    ctx.fillRect(this.x, this.y, this.width, this.height);
                }
            }
            this.newPos = function() {
                this.gravitySpeed += this.gravity;
                this.x += this.speedX;
                this.y += this.speedY + this.gravitySpeed;
                this.hitBottomOrTop();
            }
            this.hitBottomOrTop = function() {
                var rockbottom = myGameArea.canvas.height - this.height;
            
                if (this.y > rockbottom) {
                    this.y = rockbottom;
                    this.gravitySpeed = 0;
                } else if (this.y < 0) {
                    this.y = 0;
                    this.gravitySpeed = 0;
                }
            }
            this.crashWith = function(otherobj) {
                var myleft = this.x;
                var myright = this.x + (this.width);
                var mytop = this.y;
                var mybottom = this.y + (this.height);
                var otherleft = otherobj.x;
                var otherright = otherobj.x + (otherobj.width);
                var othertop = otherobj.y;
                var otherbottom = otherobj.y + (otherobj.height);
                var crash = true;
                if ((mybottom < othertop) || (mytop > otherbottom) || (myright < otherleft) || (myleft > otherright)) {
                    crash = false;
                }
                return crash;
            }
        }

        function resetGame() {
            let btn = document.createElement("button");
            btn.innerHTML = "Play again";
            btn.onclick = function () {
                $.ajax({
                    url:"increase-retries", //??bermittelt die Daten zu dbsearch
                    method:"get", //Methode der ??bermittlung
                    complete: function(data)          
                    {   
                        $.ajax({
                            url:"save-endtime.php", //??bermittelt die Daten zu dbsearch
                            method:"post", //Methode der ??bermittlung
                            complete: function(data)          
                           {  
                                window.location.reload(true);
                           }
                        });
                        
                    }
                });
                
            };
            document.body.insertBefore(btn, document.getElementById("firstDiv"));
        }

        function updateGameArea() {
            var x, height, gap, minHeight, maxHeight, minGap, maxGap;
            for (i = 0; i < myObstacles.length; i += 1) {
                if (myGamePiece.crashWith(myObstacles[i])) {
                    if(this.crashNumber == 0) {
                        resetGame();
                    }
                    this.crashNumber = 1;
                    return;
                } 
            }
            myGameArea.clear();
            myGameArea.frameNo += 1;
            if (myGameArea.frameNo == 1 || everyinterval(150)) {
                x = myGameArea.canvas.width;
                minHeight = 20;
                maxHeight = 200;
                height = Math.floor(Math.random()*(maxHeight-minHeight+1)+minHeight);
                minGap = 50;
                maxGap = 200;
                gap = Math.floor(Math.random()*(maxGap-minGap+1)+minGap);
                myObstacles.push(new component(10, height, "green", x, 0));
                myObstacles.push(new component(10, x - height - gap, "green", x, height + gap));
            }
            for (i = 0; i < myObstacles.length; i += 1) {
                myObstacles[i].x += -1;
                myObstacles[i].update();
            }
            if(everyinterval(2)) {
                accelerate(0.08);
            }
            myScore.text="SCORE: " + myGameArea.frameNo;
            myScore.update();
            myGamePiece.newPos();
            myGamePiece.update();
        }

        function everyinterval(n) {
            if ((myGameArea.frameNo / n) % 1 == 0) {return true;}
            return false;
        }

        keyPressed = false;
        
        document.body.onkeypress = function(e){
            if(checkKeyCode(e.keyCode) && !keyPressed){
                myGamePiece.gravity = -0.45;
                keyPressed = true;
            }
        }

        document.body.onkeyup = function(e){
            if(checkKeyCode(e.keyCode)){
                keyPressed = false;
            }
        }

        function checkKeyCode(keyCode) {
            return keyCode == 32;
        }

  

        function accelerate(n) {
            if( myGamePiece.gravity + n < 0.2) {
                myGamePiece.gravity += n;
            } else {
                myGamePiece.gravity = 0.2;
            }
        }


        function saveScore() {
            var participantName = document.getElementById('highscoreName').value;
            if (participantName == "") participantName = "Anonym";
            $.ajax({
                url:"save-score", //??bermittelt die Daten zu dbsearch
                method:"post", //Methode der ??bermittlung
            	data: {
					'participantName': participantName,
					'score': myGameArea.frameNo
				},
            });
            var x = document.getElementById("saveButton");
            x.remove();
        
        }
    </script>
    <div id="firstDiv">
        <input type="text" placeholder="Dein Name" id="highscoreName">
        <button id="saveButton" onClick="saveScore()">Highscore speichern</button>
    </div>


    <?php
        $db = db_connection();
        if ($db->connect_error)
        {
            die("Connection failed: " . $db->connect_error);
        } 
        //Definieren des Queries
        $sql = "SELECT name, score FROM highscore ORDER BY score DESC limit 5;";
        $result = $db->query($sql);
       
    
        while($data = $result->fetch_assoc())
        {
        
        ?> 
            <tr style="margin-top: 200px; margin-left: 500px;">
                <td class="spalte_blog"><?php echo $data['name'] ?></td>
                <td class="spalte_blog"><?php echo $data['score'] ?></td>
                
            </tr>
            <br>
        <?php
        }

    ?>


</body>