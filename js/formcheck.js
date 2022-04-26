//NICKNAME CHECK
$(document).on('keyup','#nickname',function(){
    var nickname = $("#nickname").val();
    if(nickname.match(/[<>]/g)!=null) {
        $("#nickname").removeClass("green").addClass("red");
        $("#status").html('Kallenavnet inneholder ulovlige tegn.');
    }else{
        var url = "ajax/ajax-nick.php";
        $("#status").html('<img src="./img/loader.gif">&nbsp;Sjekker tilgjengelighet...');
        $.ajax({
            type: "POST",
            url : url,
            data: $("#nickname").serialize(),
            success: function(msg)
            {
                if(msg == 'NICKOK')
                {
                    $("#nickname").removeClass("red").addClass("green");
                    $("#status").html('Tilgjengelig');
                }
                else if(msg == 'LENGTHFAIL')
                {
                    $("#nickname").removeClass("green").addClass("red");
                    $("#status").html('Kallenavnet m&aring; v&aelig;re p&aring; minst 4 tegn.');

                }
                else if(msg == 'NICKFAIL')
                {
                    $("#nickname").removeClass("green").addClass("red");
                    $("#status").html('Kallenavnet er allerede i bruk.');

                }
            }
        });
        return false;
    };
});
$(document).on('focusout','#nickname',function(){
    var nickname = $("#nickname").val();
    if(nickname.match(/[<>]/g)!=null) {
        $("#nickname").removeClass("green").addClass("red");
        $("#status").html('Kallenavnet inneholder ulovlige tegn.');
    }else{
        var url = "ajax/ajax-nick.php";
        $("#status").html('<img src="./img/loader.gif">&nbsp;Sjekker tilgjengelighet...');
        $.ajax({
            type: "POST",
            url : url,
            data: $("#nickname").serialize(),
            success: function(msg)
            {
                if(msg == 'NICKOK')
                {
                    $("#nickname").removeClass("red").addClass("green");
                    $("#status").html('Tilgjengelig');
                }
                else if(msg == 'LENGTHFAIL')
                {
                    $("#nickname").removeClass("green").addClass("red");
                    $("#status").html('Kallenavnet m&aring; v&aelig;re p&aring; minst 4 tegn.');

                }
                else if(msg == 'NICKFAIL')
                {
                    $("#nickname").removeClass("green").addClass("red");
                    $("#status").html('Kallenavnet er allerede i bruk.');

                }
            }
        });
        return false;
    }
});

//EMAIL CHECK
$(document).on('keyup','#email',function(){
    var url = "ajax/ajax-email.php";
    $("#statusemail").html('<img src="./img/loader.gif">&nbsp;Sjekker tilgjengelighet...');
    $.ajax({
        type: "POST",
        url : url,
        data: $("#email").serialize(),
        success: function(msg)
        {
            if(msg == 'EMAILOK')
            {
                $("#email").removeClass("red").addClass("green");
                $("#statusemail").html('');
            }
            else if(msg == 'EMAILINUSE')
            {
                $("#email").removeClass("green").addClass("red");
                $("#statusemail").html('E-post addressen er allerede i bruk.');
            }
            else if(msg == 'EMAILFAIL')
            {
                $("#email").removeClass("green").addClass("red");
                $("#statusemail").html('E-post addressen er ikke gyldig.');
            }
        }
    });
    return false;
});
$(document).on('focusout','#email',function(){
    var url = "ajax/ajax-email.php";
    $("#statusemail").html('<img src="./img/loader.gif">&nbsp;Sjekker tilgjengelighet...');
    $.ajax({
        type: "POST",
        url : url,
        data: $("#email").serialize(),
        success: function(msg)
        {
            if(msg == 'EMAILOK')
            {
                $("#email").removeClass("red").addClass("green");
                $("#statusemail").html('');
            }
            else if(msg == 'EMAILINUSE')
            {
                $("#email").removeClass("green").addClass("red");
                $("#statusemail").html('E-post addressen er allerede i bruk.');
            }
            else if(msg == 'EMAILFAIL')
            {
                $("#email").removeClass("green").addClass("red");
                $("#statusemail").html('E-post addressen er ikke gyldig.');
            }
        }
    });
    return false;
});
//FULLNAME CHECK
function checkFullName() {
    var fullname = $("#fullname").val();
    if(fullname.match(/[<>]/g)!=null) {
        $("#fullname").removeClass("green").addClass("red");
        $("#statusfullname").html('Navnet inneholder ulovlige tegn.');
    }else{
        if (fullname == null || fullname == "" || fullname.match(fullname.match(/([a-zA-ZæøåÆØÅ])(\s+([a-zA-ZæøåÆØÅ]{2,}))+/g))==null) {
            $("#fullname").removeClass("green").addClass("red");
            $("#statusfullname").html('Du må skrive inn hele navnet ditt.');
        }else{
            $("#fullname").removeClass("red").addClass("green");
            $("#statusfullname").html('');
        }
    }
}
$(document).ready(function() {
    $("#fullname").keyup(checkFullName);
    $("#fullname").focusout(checkFullName);
});