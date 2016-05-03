
<html>
    <head>
        <title>
            Encoder
        </title>
        <script src="jquery-2.1.4.js"></script>
        <link rel="stylesheet" type="text/css" href="style.css">
        <script type="text/javascript">
            $(document).ready(function() {
                $("#code").focus(function(){
                    $("#code").val("") ;
                });
                $("#code").on('paste', function() {
                    setTimeout(function() {
                    var code = $("#code").val();
                    code = code.trim();
                    $("#code").attr('disabled', 'disabled');
                  // console.log(code);
                   $.ajax({
                    url: "encode.php",
                    data : {'code': code},
                    type: 'post',
                    success: function (data) {
                                  
                                   $("#enc_code").text(data);
                                   $("#enc_code").select();
                                }
                      
                });
                },1000);
                });
            });
        </script>
    </head>
    <body>
        <div class="container">
            <header><h2>PHP Encoder</h2></header>
            <br>
            <div class="left-content">
                <p class="description">Paste your code here</p>

                
                    <textarea id='code'>
                
                    </textarea>
                
            </div>
            <div class="right-content">
                    <p class="description">Your encoded code</p>
                    <textarea id='enc_code' readonly>
                
                    </textarea>
            </div>
        </div>
    </body>
</html>



