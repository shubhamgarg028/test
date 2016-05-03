<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script type="text/javascript">
   
    setTimeout(check_chat, 3000)

    var check_chat = function () {
        $.ajax({
            url: 'http://api.joind.in/v2.1/talks/10889',
            data: {
                format: 'json'
            },
            error: function () {
                $('#info').html('<p>An error has occurred</p>');
            },
            dataType: 'jsonp',
            success: function (data) {
                var $title = $('<h1>').text(data.talks[0].talk_title);
                var $description = $('<p>').text(data.talks[0].talk_description);
                $('#info')
                        .append($title)
                        .append($description);
            },
            type: 'GET'
        });
    }
</script>