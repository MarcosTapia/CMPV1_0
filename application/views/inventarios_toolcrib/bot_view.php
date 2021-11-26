<!-- Creado por Coroplast -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/styleBot.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    
    <script>
        function inicio(){
            document.getElementById('data').focus();
        }
    </script>
</head>
<body onload="inicio();">
    <div class="wrapper center-block" style="margin-top: 170px;">
        <div class="title">Ayuda en línea Toolcrib</div>
        <div class="form">
            <div class="bot-inbox inbox">
                <div class="icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="msg-header">
                    <p>Hola, en que te puedo ayudar?</p>
                </div>
            </div>
        </div>
        <div class="typing-field">
            <div class="input-data">
                <input id="data" type="text" placeholder="Ingresa tu consulta.." required>
                <button id="send-btn">Send</button>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $("#send-btn").on("click", function(){
                $value = $("#data").val();
                $msg = '<div class="user-inbox inbox"><div class="msg-header"><p>'+ $value +'</p></div></div>';
                $(".form").append($msg);
                $("#data").val('');
                
                $.ajax({
                    url: '<?php echo base_url(); ?>/consultas_ajax/message.php',
                    type: 'POST',
                    data: 'text='+$value,
                    success: function(result){
                        $replay = '<div class="bot-inbox inbox"><div class="icon"><i class="fas fa-user"></i></div><div class="msg-header"><p>'+ result +'</p></div></div>';
                        $(".form").append($replay);
                        $(".form").scrollTop($(".form")[0].scrollHeight);
                    }
					
                });
            });
			
            $('#data').keypress(function(e) {
                if (e.which == 13) {
                    $value = $("#data").val();
                    $msg = '<div class="user-inbox inbox"><div class="msg-header"><p>'+ $value +'</p></div></div>';
                    $(".form").append($msg);
                    $("#data").val('');

                    $.ajax({
                            url: '<?php echo base_url(); ?>/consultas_ajax/message.php',
                            type: 'POST',
                            data: 'text='+$value,
                            success: function(result){
                                    $replay = '<div class="bot-inbox inbox"><div class="icon"><i class="fas fa-user"></i></div><div class="msg-header"><p>'+ result +'</p></div></div>';
                                    $(".form").append($replay);
                                    // when chat goes down the scroll bar automatically comes to the bottom
                                    $(".form").scrollTop($(".form")[0].scrollHeight);
                            }
                    });
                }
            });
			
        });
    </script>
    
</body>
</html>
