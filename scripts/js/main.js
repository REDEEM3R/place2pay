

var bancos = [];
var counter = 0;
var transactionPend = "";
var cookieValue = "";

$(document).ready(function(){
	var currdate = new Date;
	var fecha = '';
	fecha += currdate.getDate();
	fecha += currdate.getMonth();
	fecha += currdate.getFullYear();

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


	if(localStorage["expdatebank"] && localStorage["expdatebank"]==fecha){
		if(localStorage["banks"]){
			bancos = JSON.parse(localStorage["banks"]);
			for (let b of bancos) {
				if (b["bankcode"] == 0) {
					$("#bank-selected").append(
					"<option disabled selected name='banco' value='" +
						b["bankcode"] +
						"' id = '" +
						b["bankdesc"] +
						"'>" +
						b["bankdesc"] +
						"</option>"
					);
				} else {
					$("#bank-selected").append(
					"<option name='banco' value='" +
						b["bankcode"] +
						"' id = '" +
						b["bankdesc"] +
						"'>" +
						b["bankdesc"] +
						"</option>"
					);
				}
			}
		}	
	}else{
		recursiveAjax();
	}
});

var recursiveAjax = function() {
	var currdate = new Date;
	var fecha = '';
	fecha += currdate.getDate();
	fecha += currdate.getMonth();
	fecha += currdate.getFullYear();
	if(localStorage["expdatebank"] && localStorage["expdatebank"]==fecha){
		if(localStorage["banks"]){
			bancos = JSON.parse(localStorage["banks"]);
		}
	}else{
		$.ajax({
			url: "api/ajax/banklist.php",
			type: "POST",
			data: {"attemps":counter},
			success: function(res) {
			  bancos.length = 0;
			  res = JSON.parse(res);
			  if (res.msg == "OK") {
				$("#bank-selected")
				  .find("option")
				  .remove()
				  .end();
				for (let v of res.banklist) {
				  bancos.push(v);
				}
				localStorage["banks"] = JSON.stringify(bancos);
				var expdate = new Date;
				var fecha = '';
				fecha += expdate.getDate();
				fecha += expdate.getMonth();
				fecha += expdate.getFullYear();
				localStorage["expdatebank"] = fecha;
			  }else if (res.msg == "ERROR") {
				counter++;
				recursiveAjax();
			  }else if (res.msg == "TIMEOUT"){
				alert("TIMEOUT");
			  }
			  for (let b of bancos) {
				if (b["bankcode"] == 0) {
					$("#bank-selected").append(
					"<option disabled selected name='banco' value='" +
						b["bankcode"] +
						"' id = '" +
						b["bankdesc"] +
						"'>" +
						b["bankdesc"] +
						"</option>"
					);
				} else {
					$("#bank-selected").append(
						"<option name='banco' value='" +
						b["bankcode"] +
						"' id = '" +
						b["bankdesc"] +
						"'>" +
						b["bankdesc"] +
						"</option>"
						);
					}
				}
			}
		});
	}
}

var createTransaction = function (){
	errorCount = 0;
	var selectedData = {documenttype: "", documentno:"" , fname: "", lname: "", email: "", phone: "", bank: "", interface: ""};
	selectedData.documenttype = $('#document-type').val();
	selectedData.documentno = $('#payerdocument').val();
	selectedData.fname = $('#payername').val();
	selectedData.lname = $('#payerlastname').val();
	selectedData.email = $('#payeremail').val();
	selectedData.phone = $('#payerphone').val();
	selectedData.bank  = $('#bank-selected').val();
	selectedData.interface = $('#usuario-selected').val();
	if(selectedData.documenttype == "" || !selectedData.documenttype || selectedData.bank == "" || !selectedData.bank || selectedData.interface == "" || !selectedData.interface || selectedData.documentno == "" || !selectedData.documentno || selectedData.fname == "" || !selectedData.fname || selectedData.lname == ""  || !selectedData.lname || selectedData.email == "" || !selectedData.email || selectedData.phone == "" || !selectedData.phone 
	){
		alert("Por favor complete todos los campos");
	}else{
		params = JSON.stringify(selectedData);
		var date = Date.now();
		$.ajax({
			url: "api/ajax/createtransaction.php",
			type: "POST",
			data: {"data":params, "date":date},
			success: function(res) {
				res = JSON.parse(res);
				if(res.returnCode == "SUCCESS"){
					document.cookie = "transactionID="+res.transactionID+";expires=Thursday, 23-Aug-18 19:12:42 UTC;path=/";
					transactionPend = "res.transactionID";
					window.location.replace(res.bankURL);
				}else{
					alert("Error "+res.returnCode);
				}
			}
		});
	}
}


var getTransaction = function (tid = ''){
	var html = "";
	$.ajax({
		url: "api/ajax/gettransaction.php",
		type: "POST",
		data: {"transactionID":tid} ,
		success: function(res) {
			res = JSON.parse(res);
			if(!res.data.transactionID){
                res.data.transactionID = res.data[0].transactionID;
            }
            if(res.msg != "NOENTRY"){
                switch (res.msg) {
                    case "OK":
                        html += "<p>Su transacción con id: "+res.data.transactionID+" ha sido aprobada por el banco</p>";
                        break;
					case "NOT_AUTHORIZED":
						html += "<p>Su transacción con id: "+res.data.transactionID+" ha sido rechazada por el banco</p>";
                        break;
					case "PENDING":
						transactionPend = res.data.transactionID;
						html += "<div><p>Su transacción con id: "+res.data.transactionID+" se encuentra en estado pendiente</p></div>";
						$("#pay-mthds").html(html);
                        setTimeout(function(){
                            getTransaction(res.data.transactionID);
                          }, 5000);
                        break;
                    case "FAILED":
						console.log("Transacción fallida");
						break;
					case "WAITING":
						transactionPend = res.data.transactionID;
						html += "<div><p>Su transacción con id: "+res.data.transactionID+" se encuentra en proceso por el banco.</p></div>";
						$("#pay-mthds").html(html);
                        setTimeout(function(){
                            getTransaction(res.data.transactionID);
                          }, 70000);
                        break;
                    default:
                        break;
                }
            }else if(tid != '' && res.msg == "NOENTRY"){
				document.cookie = "transactionID="+cookieValue+";expires==; expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/";
            }else if(tid == '' && res.msg == "NOENTRY"){
				console.log("NO ENTRIES");
			}
		}
	});
}

$("#payopt-pse").click(recursiveAjax);
$("#payopt-pse").click(function(){
	if(bancos){
		$("#pay-details").css('display','block');
	}
});

$("#cancel-bank").click(function(){
	if($("#pay-details").css('display') == 'block'){
		$("#pay-details").css('display','none');
		$("#usuario-selected").val("");
		$("#banks-selected").val("");
	}
});

$("#submit-bank").click(function(){
		if(transactionPend == ""){
			createTransaction();
		}else{
			console.log("Hay una transacción en proceso");
		}
	}
);


