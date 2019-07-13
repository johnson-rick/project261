$(document).ready(function () {
    $("#addAccount").click(function () {
        var msg = "";
        var errorcount = 0;
        //check pwd matches
        var pwd_match = ($("#pwd").val() == $("#confirm_pwd").val());
        if (!pwd_match) {
            msg += 'Passwords must match! ';
            errorcount++;
        }
        var pwd = $("#pwd").val();
        //check pwd strength
        if (!(pwd.length >= 7 && hasNumber(pwd))) {
            msg += "Password must be at least 7 characters and contain a number!";
            errorcount++;
        }

        if (errorcount > 0) {
            $("#password_error").html(msg);
            $("#password_error").removeClass("hide");
            return false;
        }
        else {
            $("#password_error").html("");
            $("#password_error").addClass("hide");
            return true;
        }
    });
}); //end document.ready

function hasNumber(myString) {
    return /\d/.test(myString);
}