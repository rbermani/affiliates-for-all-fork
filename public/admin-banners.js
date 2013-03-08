function refresh_page() {
    window.location.href = window.location.href;
}

function checkName(proposedName) {
    if(proposedName.match(/[^0-9A-Za-z_.-]/)) {
        $("#illegalchar").dialog('open');
        return false;
    }

    return true;
}

function save(id) {
    if(!checkName($("#name_" + id).val()))
        return false;

    getJSON("json-alterbanner.php", {
            "id": id,
            "name": $("#name_" + id).val(),
            "linktarget": $("#linktarget_" + id).val(),
            "enabled": $("#enabled_" + id).is("[checked]") ? "1" : "0"
        }, function(result) {
            if(result == "duplicate") {
                $("#duplicate").dialog('open');
            } else {
                $("banneraltered").dialog({autoOpen: false,
                buttons: { "Ok": function() { $(this).dialog("close");
                                     refresh_page();} }});

                $("#banneraltered").dialog('open');
            }
        });

    return false;
}

function deleteBanner(id) {
    $("#confirmdelete").dialog({
        buttons: {
            "Yes": function() {
                $("#confirmdelete").dialog("close");
                getJSON("json-delbanner.php", { "id": id }, refresh_page);
            },

            "No": function() {
                $("#confirmdelete").dialog("close");
            }
        }
    });

    return false;
}

$(function() {
    $("#tabs").tabs();

    var submitBoxes = $(".bannersubmit");
    if(submitBoxes.length == 0)
        $("#tabs").tabs("select", 1).tabs("disable", 0);
    
    submitBoxes.map(function() {
        var id = $(this).find("input").attr("id").replace(/.*_/, "");

        $(this).find("input:first-child").click(function() {
            return save(id);
        });

        $(this).find("input:nth-child(2)").click(function() {
            return deleteBanner(id);
        });
    });

    $("#upload").click(function() {
        if(!checkName($("#new_name").val()))
            return false;

        return true;
    });

    var start = $("#start").text();
    if(start == "duplicate") {
        $("#tabs").tabs("select", 1);
        $("#duplicate").dialog('open');
    } else if(start == "success") {
        $("#success").dialog('open');
    }
});
