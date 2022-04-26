$(document).on('keyup','#password',function(){
    var password = $("#password").val();
    if(password.match(/[<>]/g)!=null) {
        $("#password").removeClass("green").addClass("red");
        $("#pwstatus").html('Passordet inneholder ulovlige tegn.');
    }else{
        var url = "ajax/ajax-pwd.php";
        $("#pwstatus").html('<img src="./img/loader.gif">&nbsp;Sjekker...');
        $.ajax({
            type: "POST",
            url : url,
            data: $("#password").serialize(),
            success: function(msg)
            {	if(msg == 'PWDEMPTY')
            {
                $("#password").removeClass("green").removeClass("yellow").removeClass("red");
                $("#pwstatus").html('');

            }
            else if(msg == 'PWDINVALIDCHAR')
            {
                $("#password").removeClass("green").removeClass("yellow").addClass("red");
                $("#pwstatus").html('&aelig;,&oslash og &aring er ikke tillatt.');

            }
            else if(msg == 'PWDTOSHORT')
            {
                $("#password").removeClass("green").removeClass("yellow").addClass("red");
                $("#pwstatus").html('For kort!');

            }
            else if(msg == 'PWDFAIL')
            {
                $("#password").removeClass("green").removeClass("red").addClass("yellow");
                $("#pwstatus").html('Passordet m&aring; inneholde minst en stor og en liten bokstav, et tall og et spesial tegn.');
            }
            else if(msg == 'PWDSTRONG')
            {
                $("#password").removeClass("red").removeClass("yellow").addClass("green");
                $("#pwstatus").html('Godkjent!');
            }
            }
        });
        return false;
    }
});

function checkPasswordMatch() {
    var password = $("#password").val();
    var password2 = $("#password2").val();

    if (password != password2) {
        $("#password2").removeClass("green").addClass("red");
        $("#pwstatus2").html("Passordene er ikke like!");
    }else{
        $("#password2").removeClass("red").addClass("green");
        $("#pwstatus2").html("Passordene er like!");
    }
}
$(document).ready(function() {
    $("#password2").keyup(checkPasswordMatch);
    $("#password2").focusout(checkPasswordMatch);
});