var timer = null, passGood = false, wizard = false, emailGood = true,
    userGood=false;

function emailChange(e) {
    if(timer != null)
        window.clearTimeout(timer);
    timer = setTimeout(availEmail, 500);
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

function availEmail() {
    var email = $("#email").val();
    var message = checkEmail(email);
    var oldEmail = $("#useremail").contents().text();

    if (oldEmail != email) {
    if (emailGood){

        getJSON("json-availuser.php", { "user": email,
            "parent_user": ''},
        function(json) {
            userGood = json[0];
            $("#message").html(json[1]);
        });
    } else {
        $("#message").html(message);
    }
    }
}

function agree() {

 //   $("tabs").tabs("disabled", []);
    $("#tabs").tabs("enable", 1);
    $("#tabs").tabs("enable", 2);
    
    $("#tabs").tabs("select", 1);
//    $("#tabs").tabs("disable", 0);
    
    return false;
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

function setGeneral() {
    var required = $("#firstname, #lastname, #email, #address1, #address2, " +
        "#postcode");

    if(required.is('*[value=""]') || emailGood != true) {
        $("#requiredfields").dialog('open');
    } else {
        getJSON("json-setgeneral.php",
            { "details": $("#general :input").serialize() },
            function() {
                if(wizard) {
                    $("#tabs").tabs("select", 2);
                } else {
                    $("#generalset").dialog('open');
                }
            });
    }

    return false;
}

function setPassword() {
    if(passGood) {
        getJSON("json-setpassword.php",
            { "password": $("#chosenpassword").val() },
                function() { $("#pwdset").dialog('open'); });
    } else {
        $("#failedtosetpwd").dialog('open');
    }

    return false;
}

function setPayment() {
    var paypalchecked = $("#paypal_checked").attr("checked");
    var checkchecked = $("#check_checked").attr("checked");

    if (checkchecked != 0) {
        checkchecked = 1;
    } else {
        checkchecked = 0;
    }

    getJSON("json-setpayment.php",
        { "paypal": $("#paypalid").val(),
        "checkchecked": checkchecked},
        function() {
            if(wizard) {
                $("#endofwizard").dialog('open');
            } else {
                $("#paypalset").dialog('open');

            }
        });

    return false;
}

jQuery(function($) {
    $("#tabs").tabs();
    $("#chosenpassword").change(passChange);
    $("#chosenpassword").keyup(passChange);
    $("#confirmpassword").change(passChange);
    $("#confirmpassword").keyup(passChange);
    $("#email").change(emailChange);
    $("#email").keyup(emailChange);
    $("#general > form").submit(setGeneral);
    $("#password > form").submit(setPassword);
    $("#payment > form").submit(setPayment);

$("#failedtosetpwd").dialog({ autoOpen: false,
                                 buttons: { "Ok": function() { $(this).dialog("close"); } } });

$("#paypalset").dialog({ autoOpen: false,
                                 buttons: { "Ok": function() { $(this).dialog("close"); } } });

$("#pwdset").dialog({ autoOpen: false,
                                 buttons: { "Ok": function() { $(this).dialog("close"); } } });

$("#generalset").dialog({ autoOpen: false,
                                 buttons: { "Ok": function() { $(this).dialog("close"); } } });

$("#requiredfields").dialog({ autoOpen: false,
                                 buttons: { "Ok": function() { $(this).dialog("close"); } } });

$("#endofwizard").dialog ({
    autoOpen: false,
    buttons: { "Ok": function() { $(this).dialog("close");
        window.location = window.location.href.replace(/[^/]*$/, "") + "overview.php";}}});


    if($("#legal > form").length > 0) {
        $("#legal > form").submit(agree);
        $("#tabs").tabs("option", "disabled", [1,2,3]);
   //     $("#tabs").tabs("remove", 3);
        

        $("#general :submit").val("Next");
        $("#paypal :submit").val("Finish");

        wizard = true;
    } else {
        $("#tabs").tabs("select", 1);
    }
});
