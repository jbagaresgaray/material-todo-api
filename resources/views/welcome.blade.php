<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 96px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <!--<div class="title">Laravel 5</div>-->

                <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
                <script src="https://cdn.socket.io/socket.io-1.3.4.js"></script>
                
                <div class="container">
                    <div class="row">
                        <p id="power">0</p>
                    </div>
                </div>
                <script>
                    var socket = io('http://api.youprodev.com:3000');
                    socket.on("server-updates:App\\Events\\ServerUpdated", function(message){
                        // increase the power everytime we load test route
                        $('#power').text(message.data);
                    });
                </script>


            </div>
        </div>
    </body>
</html>
