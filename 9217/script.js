$(document).ready(function(){
	$('.inputLabel').click(function(e) {
		$(this).parent().parent().addClass("active");
		$(this).parent().find(".textInput").show();
		$(this).parent().find(".textInput").focus();
		$(this).parent().find(".inputLabel").hide();
	});
	$('.textInput').blur(function() {
		var value = $(this).val();
		$(this).parent().parent().removeClass("active");
		if(value=="") {
			$(this).parent().find(".inputLabel").show();
			$(this).parent().find(".textInput").hide();
		}
	});
	
	$("#searchButton").click(function(e) {
		$("#formSearchHelp").submit();
	});
	
	$(".searchInputBox input").val("");
	showBanner(1);
	monkeyPatchAutocomplete();
	
	$('#sidebar a.years').click(function(){
		if ( $(this).siblings('ul').css('display') =='none' ){
			$(this).siblings('ul').show();
		}
		else
		{
			$(this).siblings('ul').hide()
		}		
	});
	
	$(".loginDiv .login").click(function(){
        doLogin();
    });
	
	function doLogin() {
        $("#txtHandle").removeClass("error");
        $("#txtPassword").removeClass("error");
        var handle = $("#txtHandle").val();
        var password = $("#txtPassword").val();
        var error = false;
        if(handle == ""){
            error = true;
            $("#txtHandle").addClass("error");
        }
        if(password == ""){
            error = true;
            $("#txtPassword").addClass("error");
        }
        if(!error){
            $('form[name="frmLogin"]').submit();
        }
    }

});

function showBanner(i) {
	$(".descItem").hide();
	$("#banner"+i).show();
	$(".bannerTabWrapper li").removeClass("active");
	$("#tab"+i).addClass("active");
}

function monkeyPatchAutocomplete() {

    $.ui.autocomplete.prototype._renderItem = function (ul, item) {
            item.label = item.label.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + $.ui.autocomplete.escapeRegex(this.term) + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<strong>$1</strong>");
            return $("<li></li>")
                    .data("item.autocomplete", item)
                    .append("<a>" + item.label + "</a>")
                    .appendTo(ul);
        };
}

