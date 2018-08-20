
var cookieValue = "";

$(document).ready(function(){

    var name = "transactionID=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            cookieValue +=  c.substring(name.length, c.length);
        }
    }

    if(cookieValue != '' && cookieValue != undefined){
        getTransaction(cookieValue);
    }else{
        getTransaction(cookieValue);
    }
});

var getTransaction = function (tid = ''){
    var html = "";
	$.ajax({
		url: "../../api/ajax/gettransaction.php",
		type: "POST",
		data: {"transactionID":tid} ,
		success: function(res) {
            res = JSON.parse(res);
            console.log(res);
            if(!res.data.transactionID){
                res.data.transactionID = res.data[0].transactionID;
            }
            if(res.msg != "NOENTRY"){
                switch (res.msg) {
                    case "OK":
                        html += "<p>Su transacción con id: "+res.data.transactionID+" ha sido aprobada por el banco. Redireccionando al menú de pago.</p>";
                        setTimeout(function(){
                            window.location.replace("../../index.html");
                          }, 5000);
                        break;
                    case "NOT_AUTHORIZED":
                        html += "<p>Su transacción con id: "+res.data.transactionID+" ha fallado. Será redireccionado al menú de pago</p>";
                        setTimeout(function(){
                            window.location.replace("../../index.html");
                        }, 5000);
                        break;
                    case "PENDING":
                        html += "<p>Su transacción con id: "+res.data.transactionID+" se encuentra en estado pendiente</p>";
                        setTimeout(function(){
                            getTransaction(res.data.transactionID);
                          }, 5000);
                        break;
                    case "FAILED":
                        html += "<p>Su transacción con id: "+res.data.transactionID+" ha sido rechazada por el banco. Será redireccionado al menú de pago</p>";
                        setTimeout(function(){
                            window.location.replace("../../index.html");
                          }, 5000);
                        break;
                    case "WAITING":
                        html += "<p>Su transacción con id: "+res.data.transactionID+" está siendo procesada";
                        setTimeout(function(){
                            getTransaction(res.data.transactionID);
                        }, 5000);
                        break;
                    default:
                        break;
                }
            }else{
                alert("Usted no tiene ninguna transacción en proceso. Será redireccionado al Índice");
                window.location.replace("../../index.html");
            }
            $("#details-response").html(html);
		}
	});
}