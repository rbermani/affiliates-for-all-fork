var timer = null, userGood = false, passGood = false, parentUserExists = false,
    emailGood = false;

function userChange(e) {
    if(timer != null)
        window.clearTimeout(timer);
    timer = setTimeout(checkUser, 500);
}

function checkEmail(email) {

    var atPos = email.indexOf("@");
    var stopPos = email.lastIndexOf(".");
    var length = email.length - 1;
    var message = "";

    if (email == "" || atPos == -1 || stopPos == -1 || stopPos < atPos ||
        stopPos - atPos == 1 || stopPos == length) {
        message = "<div class=\"no\">Not a valid Email address</div>";
        emailGood = false;
    } else {
        emailGood = true;
    }
    return message;
}

function checkUser() {

    var email = $("#chosenusername").val();
    var message = checkEmail(email);

    if (emailGood){

        getJSON("json-availuser.php", { "user": email,
            "parent_user": $("#parentuser").val()},
        function(json) {

            userGood = json[0];
            $("#message").html(json[1]);
            parentUserExists = json[2];
            $("#message2").html(json[3]);
        });
    } else {
        $("#message").html(message);
    }


}

function passChange() {
    var msg, cssClass;

    if($("#chosenpassword").val() == "") {
        msg = "Please choose a password.";
        cssClass = "no";
        passGood = false;
    } else if($("#chosenpassword").val() == $("#confirmpassword").val()) {
        msg = "Passwords match.";
        cssClass = "yes";
        passGood = true;
    } else {
        msg = "Passwords don’t match.";
        cssClass = "no";
        passGood = false;
    }

    $("#passmessage").text(msg).removeClass("yes").removeClass("no")
        .addClass(cssClass);
}

function logon() {

    getJSON("json-logon.php", {
        "user": $("#username").val(),
        "password": $("#password").val() }, function(json) {
    if(json) {
        window.location.href = window.location.href.replace(/[^/]*$/, "") + json;
    } else {
       $("#failedtologon").dialog('open');
    }});

    return false;
}

function navigate(json) {
    if(json)
        window.location.href = window.location.href.replace(/[^/]*$/, "") + json;
}

function signup() {


    if(userGood && passGood && parentUserExists && emailGood) {
        getJSON("json-signup.php", {
            "user": $("#chosenusername").val(),
            "password": $("#chosenpassword").val(),
            "parent_user": $("#parentuser").val() },
            navigate);
    } else if (userGood && passGood && emailGood) {
        getJSON("json-signup.php", {
            "user": $("#chosenusername").val(),
            "password": $("#chosenpassword").val(),
            "parent_user": ''},
            navigate);
    } else {
        $("#failedtosignup").dialog('open');
    }

    return false;
}

jQuery(function($) {
    $("#tabs").tabs();
    $("#parentuser").change(userChange);
    $("#parentuser").keyup(userChange);
    $("#chosenusername").change(userChange);
    $("#chosenusername").keyup(userChange);
    $("#chosenpassword").change(passChange);
    $("#chosenpassword").keyup(passChange);
    $("#confirmpassword").change(passChange);
    $("#confirmpassword").keyup(passChange);
    $("#failedtologon").dialog({ autoOpen: false,
                                 buttons: { "Ok": function() { $(this).dialog("close"); } } });
    $("#failedtosignup").dialog({ autoOpen: false,
                                buttons: { "Ok": function() { $(this).dialog("close"); } } });

    $("#logon > form").submit(logon);
    $("#signup > form").submit(signup);

});
