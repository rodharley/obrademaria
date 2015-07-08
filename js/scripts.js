
function mascaraFone(campoData){
	
     var data = campoData.value;
	 	 if (data.length == 1){
          data = '('+ data ;
          campoData.value = data;
         return true;              
	 }
	 if (data.length == 3){
          data = data + ') ';
          campoData.value = data;
         return true;              
	 }
     if (data.length == 9){
          data = data + '-';
          campoData.value = data;
          return true;
     }
}

function mascaraCep(campoData){
	
     var data = campoData.value;
	 	 if (data.length == 2){
          data = data + '.' ;
          campoData.value = data;
         return true;              
	 }
	 if (data.length == 6){
          data = data + '-';
          campoData.value = data;
         return true;              
	 }
}


// JavaScript Document
function validaCampoBranco(label,campo){
cp = window.document.getElementById(campo);

if(cp.value == ""){
cp.style.backgroundcolor = "#CCCCCC";
return label+" não pode ser vazio.\n";
}else
return "";
}


function validaCampo(id,tipo){
cp = window.document.getElementById(id);
//show apenas mostra erro sem validar
if(tipo =="show"){
		window.document.getElementById('error-'+id+'Erro').style.display = '';	
		window.document.getElementById(id).className = "inp-form-error";
		return "erro";
}
if(tipo =="num"){
	if(!isNaN(cp.value)){
		window.document.getElementById('error-'+id).style.display = '';
		window.document.getElementById(id).className = "inp-form-error";
		return "erro";
	}
}
if(tipo =="mail"){
	if(!validaEmail(cp.value)){
		window.document.getElementById('error-'+id).style.display = '';	
		window.document.getElementById(id).className = "inp-form-error";
		return "erro";
	}
}
if(tipo =="bra"){
	if(cp.value == ""){
		window.document.getElementById('error-'+id).style.display = '';	
		window.document.getElementById(id).className = "inp-form-error";
		return "erro";
	}
}

return "";
}


 function somente_numero(campo){  
 var digits="0123456789"  
 var campo_temp   
     for (var i=0;i<campo.value.length;i++){  
         campo_temp=campo.value.substring(i,i+1)   
         if (digits.indexOf(campo_temp)==-1){  
             campo.value = campo.value.substring(0,i);  
         }  
    }  
 }  
 
 function somente_cep(campo){  
 var digits="0123456789.-"  
 var campo_temp   
     for (var i=0;i<campo.value.length;i++){  
         campo_temp=campo.value.substring(i,i+1)   
         if (digits.indexOf(campo_temp)==-1){  
             campo.value = campo.value.substring(0,i);  
         }  
    }  
 }  
 
 function somente_data(campo){  
 var digits="0123456789/"  
 var campo_temp   
     for (var i=0;i<campo.value.length;i++){  
         campo_temp=campo.value.substring(i,i+1)   
         if (digits.indexOf(campo_temp)==-1){  
             campo.value = campo.value.substring(0,i);  
         }  
    }  
 }  
 
 function somente_numeroDecimal(campo){  
 var digits="0123456789,."  
 var campo_temp   
     for (var i=0;i<campo.value.length;i++){  
         campo_temp=campo.value.substring(i,i+1)   
         if (digits.indexOf(campo_temp)==-1){  
             campo.value = campo.value.substring(0,i);  
         }  
    }
	
	if (campo.value.length == 1){
        campo.value = campo.value + ',';
    }
	
 }  
 
 function somente_numeroReal(campo){  
 var digits="123456789"  
 var campo_temp   
     for (var i=0;i<campo.value.length;i++){  
         campo_temp=campo.value.substring(i,i+1)   
         if (digits.indexOf(campo_temp)==-1){  
             campo.value = campo.value.substring(0,i);  
         }  
    }  
 }  
 function valida_cpf(cpf)
      {
      var numeros, digitos, soma, i, resultado, digitos_iguais;
      digitos_iguais = 1;
      if (cpf.length < 11)
            return false;
      for (i = 0; i < cpf.length - 1; i++)
            if (cpf.charAt(i) != cpf.charAt(i + 1))
                  {
                  digitos_iguais = 0;
                  break;
                  }
      if (!digitos_iguais)
            {
            numeros = cpf.substring(0,9);
            digitos = cpf.substring(9);
            soma = 0;
            for (i = 10; i > 1; i--)
                  soma += numeros.charAt(10 - i) * i;
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            if (resultado != digitos.charAt(0))
                  return false;
            numeros = cpf.substring(0,10);
            soma = 0;
            for (i = 11; i > 1; i--)
                  soma += numeros.charAt(11 - i) * i;
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            if (resultado != digitos.charAt(1))
                  return false;
            return true;
            }
      else
            return false;
      }


function mascaraData(campoData){
	
              var data = campoData.value;
	              if (data.length == 2){
                  data = data + '/';
                  campoData.value = data;
      return true;              
              }
              if (data.length == 5){
                  data = data + '/';
                 campoData.value = data;
                  return true;
              }
         }



function mascaraCnpj(id){
	
              var campo = window.document.getElementById(id);	
			  var data = campo.value;

	           if (data.length == 2){
                data = data + '.';
                campo.value = data;
      			return true;              
              }
              if (data.length == 6){
                data = data + '.';
                campo.value = data;
                return true;
              }
			  if (data.length == 10){
                data = data + '/';
                campo.value = data;
                return true;
              }
			  if (data.length == 15){
                data = data + '-';
                campo.value = data;
                return true;
              }
         }

function ismobile(){
var mobile = (/iphone|ipad|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));
              if (mobile)
                  return true;				
              else
                  return false;
}
//-----------------------------------------------------
//Funcao: MascaraMoeda
//Sinopse: Mascara de preenchimento de moeda
//Parametro:
//   objTextBox : Objeto (TextBox)
//   SeparadorMilesimo : Caracter separador de milésimos
//   SeparadorDecimal : Caracter separador de decimais
//   e : Evento
//Retorno: Booleano
//Autor: Gabriel Fróes - www.codigofonte.com.br
//-----------------------------------------------------
function MascaraMoeda(objTextBox, SeparadorMilesimo, SeparadorDecimal, e){
    if(!ismobile()){	
	var sep = 0;
    var key = '';
    var i = j = 0;
    var len = len2 = 0;
    var strCheck = '-0123456789';
    var aux = aux2 = '';
    //var whichCode = (window.Event) ? e.which : e.keyCode;
	if(navigator.appName == 'Microsoft Internet Explorer'){
		var whichCode = e.keyCode;
	}else if(navigator.appName == 'Netscape'){
		var whichCode = e.which;
	}
    if (whichCode == 13 || whichCode == 8 || whichCode == 0) return true;
    key = String.fromCharCode(whichCode); // Valor para o código da Chave
    if (strCheck.indexOf(key) == -1) return false; // Chave inválida
    len = objTextBox.value.length;
    for(i = 0; i < len; i++)
        if ((objTextBox.value.charAt(i) != '0') && (objTextBox.value.charAt(i) != SeparadorDecimal)) break;
    aux = '';
    for(; i < len; i++)
        if (strCheck.indexOf(objTextBox.value.charAt(i))!=-1) aux += objTextBox.value.charAt(i);
    aux += key;
    len = aux.length;
    if (len == 0) objTextBox.value = '';
    if (len == 1) objTextBox.value = '0'+ SeparadorDecimal + '0' + aux;
    if (len == 2) objTextBox.value = '0'+ SeparadorDecimal + aux;
    if (len > 2) {
        aux2 = '';
        for (j = 0, i = len - 3; i >= 0; i--) {
            if (j == 3) {
                aux2 += SeparadorMilesimo;
                j = 0;
            }
            aux2 += aux.charAt(i);
            j++;
        }
        objTextBox.value = '';
        len2 = aux2.length;
        for (i = len2 - 1; i >= 0; i--)
        objTextBox.value += aux2.charAt(i);
        objTextBox.value += SeparadorDecimal + aux.substr(len - 2, len);
    }
    return false;
	}else{
		return true;
	}
}

function validaCnpj(txtCNPJ) {
    var dg1, dg2, dg3, dg4, dg5, dg6, dg7, dg8, dg9, dg10, dg11, dg12, dg13,dg14
    var total_rv = 0
    var resto_rv = 0
    var dg13_rv, dg14_rv
    var CNPJ2, CNPJ2_rv

    if ((txtCNPJ.length != 14) || (isNaN(txtCNPJ)==true)){    
        return false
    }
    else{
        if(txtCNPJ == "00000000000000"){
            return false;
        }

        dg1  = txtCNPJ.substring(0 ,1)  - 0
        dg2  = txtCNPJ.substring(1 ,2)  - 0
        dg3  = txtCNPJ.substring(2 ,3)  - 0
        dg4  = txtCNPJ.substring(3 ,4)  - 0
        dg5  = txtCNPJ.substring(4 ,5)  - 0
        dg6  = txtCNPJ.substring(5 ,6)  - 0
        dg7  = txtCNPJ.substring(6 ,7)  - 0
        dg8  = txtCNPJ.substring(7 ,8)  - 0
        dg9  = txtCNPJ.substring(8 ,9)  - 0
        dg10 = txtCNPJ.substring(9 ,10) - 0    
        dg11 = txtCNPJ.substring(10,11) - 0
        dg12 = txtCNPJ.substring(11,12) - 0
        dg13 = txtCNPJ.substring(12,13) - 0
        dg14 = txtCNPJ.substring(13,14) - 0

        total_rv = (dg1 * 5) + (dg2 * 4) + (dg3 * 3) + (dg4 * 2) + (dg5 * 9) 
        + (dg6 * 8) + (dg7 * 7) + (dg8 * 6) + (dg9 * 5) + (dg10 * 4) + (dg11 *
            3) + (dg12 * 2)
        resto_rv = total_rv % 11

        if( resto_rv > 1){
            dg13_rv = 11 - resto_rv
        }
        else{
            dg13_rv = 0
        }

        total_rv = (dg1 * 6) + (dg2 * 5) + (dg3 * 4) + (dg4 * 3) + (dg5 * 2) 
        + (dg6 * 9) + (dg7 * 8) + (dg8 * 7) + (dg9 * 6) + (dg10 * 5) + (dg11 *
            4) + (dg12 * 3) + (dg13_rv * 2)
        resto_rv = total_rv % 11        

        if (resto_rv > 1){
            dg14_rv = 11 - resto_rv
        }
        else{
            dg14_rv = 0
        }

        CNPJ2 = dg13 + "" + dg14
        CNPJ2_rv = dg13_rv + "" + dg14_rv

        if (CNPJ2 != CNPJ2_rv) {
            return false
        }
        else{
            return true
        }
    }
}

function validaCpf(cpf)
      {
      var numeros, digitos, soma, i, resultado, digitos_iguais;
      digitos_iguais = 1;
      if (cpf.length < 11)
            return false;
      for (i = 0; i < cpf.length - 1; i++)
            if (cpf.charAt(i) != cpf.charAt(i + 1))
                  {
                  digitos_iguais = 0;
                  break;
                  }
      if (!digitos_iguais)
            {
            numeros = cpf.substring(0,9);
            digitos = cpf.substring(9);
            soma = 0;
            for (i = 10; i > 1; i--)
                  soma += numeros.charAt(10 - i) * i;
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            if (resultado != digitos.charAt(0))
                  return false;
            numeros = cpf.substring(0,10);
            soma = 0;
            for (i = 11; i > 1; i--)
                  soma += numeros.charAt(11 - i) * i;
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            if (resultado != digitos.charAt(1))
                  return false;
            return true;
            }
      else
            return false;
      }

function validaCpfCnpj(txt){
if(txt.length == 11)
	return validaCpf(txt);
else
	return validaCnpj(txt);
}

function validaEmail(txt){
	if((txt.length != 0) && (txt.indexOf("@")  < 1 || (txt.indexOf(".") < 1))){
		return false;
	}else{
		return true;
	}
}

$(document).ready(function(){
	$('#bt_voltar').click(function() {
		history.go(-1);
	}); 
		
	$('#bt_print').click(function() {
		window.print();
	});
	
	$('#bt_enviaRelat').click(function() {									  
		$("#textoEmailRelat").val($("#tabelaRelat").html());
		jQuery.ajax({
		type: "POST",
		url: "ajax/ajax.enviarEmail.php?ajax=1",
		data: { textoEmailRelat: $("#textoEmailRelat").val(), infoEmailRelat: $("#infoEmailRelat").val(), destinatario: $("#destinatario").val() },
		beforeSend: function( ) {
		jQuery('#boxRelatEmail').html('<p align="center">Processando...<br /><img src="img/ajax_loading_pequeno.gif" /></p>');
		  },
		success: function(msg){
		jQuery("#boxRelatEmail").html(msg);
		}
		});
	});

});
