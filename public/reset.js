
document.write('<script type="text/javascript" src="http://api.recaptcha.net/js/recaptcha_ajax.js"></script>');

function showRecaptcha(pubkey, element)
{
 Recaptcha.create(pubkey,element,{
 theme: "red",
 callback: Recaptcha.focus_response_field})
}
function verifyConfirm()
{

    getJSON("json-checkconfirm.php", {
        "email": $("#email_conf").val(),
        "confirmcode": $("#confirmcode").val()},function(json){

        if (json == 1) {
            $("#message").text("Your confirmation code is correct, please enter new password below.");
            $("#newpassword").show();
        } else if (json == 2) {
            $("#message").text("Your confirmation code has expired, please resubmit request.");
        } else if (json == 3){
            $("#message").text("The confirmation code entered is incorrect.");
        } else if (json == 4){
            $("#message").text("No such email address");
        }
    });

    return false;
    
}

function changePassword()
{

    if ($("#newpass").val() != $("#newpassconfirm").val()) {
        $("#message").text("Your passwords do not match.");
    } else {
        getJSON("json-resetpassword.php", {
            "email": $("#email_conf").val(),
            "confirmcode": $("#confirmcode").val(),
            "newpass": $("#newpass").val()
            }, function(json){

            if (json == 1) {
                $("#message").text("Password has been successfully changed.");
               
            } else if (json == 2) {
                $("#message").text("Your confirmation code has expired, please resubmit request.");
            } else if (json == 3){
                $("#message").text("The confirmation code entered is incorrect.");
            } else if (json == 4){
                $("#message").text("Password change has failed");
            }
        });
    }

    return false;
}

function resetPass()
{

    $("#challengeData").val(Recaptcha.get_challenge());
    $("#responseData").val(Recaptcha.get_response());

    getJSON("json-reset.php", { "challengeData": $("#challengeData").val(),
    "responseData": $("#responseData").val(),
    "email": $("#email").val()},
        function(json) {
            if (json == 1){
                $("#message").text('Your confirmation code has been emailed. Please make sure to check your SPAM'
           + ' folder, as some clients can mistakenly mark the email as SPAM.');
                $("#confirm_code").show();
                $("#captcha_entry").hide();
                $("#recaptcha").hide();

            } else if (json == 2){
                $("#message").text("That Email address does not exist in our system");
            } else if (json == 3){
                $("#message").text("Incorrect reCAPTCHA data.");
            } else if (json == 5) {
                $("#message").text("Email send failed.");
            }else {
                $("#message").text("You are missing a data field.");
            }}); 

    return false;
}

jQuery(function($) {

//   $("#confirmcode").hide();
//   $("#newpassword").hide();
   var publickey;
   publickey = $("#captcha_key").text();
   $("#alreadyconfirm").click(function() {
       // $("#recaptcha").hide();
       // $("#captcha_entry").hide();
      //  $("#status").hide();
        $("#confirm_code").show();
   });


   showRecaptcha(publickey,'recaptcha');
   $("#captcha_entry > form").submit(resetPass);
   $("#confirm_code > form").submit(verifyConfirm);
   $("#newpassword > form").submit(changePassword);

   $("#newpassword").hide();
   $("#confirm_code").hide();
   
});