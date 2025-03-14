function UpperCase(texto){
    texto.value=texto.value.toUpperCase();
}
function LowerCase(texto){
    texto.value=texto.value.toLowerCase();
}
function roundNumber(num, dec) {
    var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
    return result;
}
function ValidaVacio(Elemento,campo,requerido)
{
    if ((Elemento.value=="") && (requerido==true))
    {
        alert("Debe ingresar " + campo)
        Elemento.focus();
        if (Elemento.type=="Text")
            Elemento.select();
        return false;
    }
	
    return true;

}

function ValidaRadioButton(Elemento,campo,requerido)
{
    var cnt = -1;
    for (var i=Elemento.length-1; i > -1; i--) 
    {
        if (Elemento[i].checked) 
        {
            cnt = i; 
            i = -1;
        }
    }
    if (cnt==-1)
    {
        alert("Debe Seleccionar " + campo);
        return false;
    }
    else
        return true;

}

function ValidaCheckBox(form,Elemento,campo,requerido)
{
    var cnt = -1;
    for (var i=form.length-1; i > -1; i--) 
    {
        E=form.elements[i]; 
        if (E.type=='checkbox' && E.checked==true && E.name.indexOf(Elemento)==0)
        {
            cnt = i; 
            i = -1;
        }
    }
    if (cnt==-1)
    {
        alert("Debe Seleccionar " + campo);
        return false;
    }
    else
        return true;

}

function ValidaFile(Elemento,campo,requerido,iType) 
{
    var file=Elemento.value;
    //var iType=1;
    //if (ValidaVacio(Elemento,campo,requerido)==false)
    //return false;
    if (iType==400) 
        extArray = new Array("jpg","tif","gif","pdf","doc","docx","xsl","xslx");
		
		
    if (iType==4) 
        extArray = new Array("wmv","mpeg","avi","mov");
    if (iType==3) 
        extArray = new Array("gif","jpg","bmp");
    if (iType==30) 
        extArray = new Array("swf");
    if (iType==100) 
        extArray = new Array("swf");	
    if (iType==2)
        extArray = new Array("pdf");
    if (iType==1)
        extArray = new Array("pdf");
    if (iType==10)
        extArray = new Array("doc");
    if (iType==11)
        extArray = new Array("csv");
    if (iType==110)
        extArray = new Array("rar");
    allowSubmit = false;
	
	
    if (Elemento.value!="")
    {
        while (file.indexOf("\\") != -1) file = file.slice(file.indexOf("\\") + 1);
        while (file.indexOf(".") != -1) file = file.slice(file.indexOf(".") + 1);
        ext = file.toLowerCase();
        for (var i = 0; i < extArray.length; i++) 
        {
            if (extArray[i] == ext) 
            {
                allowSubmit = true;
                break;
            }
        } 
		
        if (!allowSubmit) 
        {
            alert("Debe ingresar un archivo con extensiones " + (extArray.join(" ")) + " en el campo " + campo);
            return false;
        }
    }
    else{
    	alert("Debe ingresar un archivo");
    	return false;
    }
    return true;
} //FIN VALIDA ARCHIVO
function ValidaNumero(Elemento,campo,requerido)
{
    if (ValidaVacio(Elemento,campo,requerido)==false)
        return false;
    if (Elemento.value!="")
    {
        exprecion=/^([0-9])*$/;
        if (exprecion.test(Elemento.value))
            return true
        else
        {
            alert("Debe ingresar solo números  en el campo " + campo)
            Elemento.focus();
            Elemento.select();
            return false;
        }
    }
    return true;
}
function ValidaDecimal(Elemento,campo,requerido)
{
    if (ValidaVacio(Elemento,campo,requerido)==false)
        return false;
    if (Elemento.value!="")
    {
        exprecion=/^([0-9.])*$/;
        if (exprecion.test(Elemento.value))
            return true
        else
        {
            alert("Debe ingresar solo decimales separados por '.' en el campo " + campo)
            Elemento.focus();
            Elemento.select();
            return false;
        }
    }
    return true;
}
function ValidaNumero31(Elemento,campo,requerido)
{
    if (ValidaVacio(Elemento,campo,requerido)==false)
        return false;
    if (Elemento.value!="")
    {
        var ndias_rem_ant = document.getElementById('ndias_rem_ant').value;
        exprecion=/^([0-9])*$/;
        if (exprecion.test(Elemento.value))
            if(ndias_rem_ant>0 && ndias_rem_ant<32){
                return true;
            }else{
                alert("Debe ingresar de 1 a 31 dias " + campo)
                Elemento.focus();
                Elemento.select();
                return false;
            }
        else
        {
            alert("Debe ingresar solo números  en el campo " + campo)
            Elemento.focus();
            Elemento.select();
            return false;
        }
    }
    return true;
}
function cumpleReglas(simpleTexto)
{
    //la pasamos por una poderosa expresi?n regular
    var expresion = new RegExp("^(|([0-9]{1,10}(\\.([0-9]{0,2})?)?))$");

    //si pasa la prueba, es v?lida
    if(expresion.test(simpleTexto))
        return true;
    return false;
}//end function checaReglas

//ESTA FUNCI?N REVISA QUE TODO LO QUE SE ESCRIBA EST? EN ORDEN
function revisaCadena(textItem)
{
    //si comienza con un punto, le agregamos un cero
    if(textItem.value.substring(0,1) == '.') 
        textItem.value = '0' + textItem.value;

    //si no cumples las reglas, no te dejo escribir
    if(!cumpleReglas(textItem.value))
    {
        try
        {
            textItem.value = textoAnterior;
        }
        catch (e)
        {
            textItem.value='';
        };
            	
    }
    else //todo en orden
        textoAnterior = textItem.value;
}//end function revisaCadena


function ValidaDecimal(Elemento,campo,requerido)
{
    if (ValidaVacio(Elemento,campo,requerido)==false)
        return false;
    if (Elemento.value!="")
    {
        // expresion de: http://regexlib.com/DisplayPatterns.aspx?cattabindex=2&amp;categoryId=3
        exprecion=/^\d*[0-9](\.\d*[0-9])?$/;
        if (exprecion.test(Elemento.value))
            return true
        else
        {
            alert("Debe ingresar formato con separador decimal '.' en " + campo)
            Elemento.focus();
            Elemento.select();
            return false;
        }
    }
    return true;
}

function ValidaLetras(Elemento,campo,requerido)
{
    if (ValidaVacio(Elemento,campo,requerido)==false)
        return false;
    if (Elemento.value!="")
    {
        exprecion=/^([A-Za-z??????????????¡?Õ????·È'\s])*$/;
        if (exprecion.test(Elemento.value))
            return true
        else
        {
            alert("Debe ingresar solo letras en " + campo)
            Elemento.focus();
            Elemento.select();
            return false;
        }
    }
    return true;
}

function ValidaMaximoMinimoIgual(Elemento, campo, requerido)
{
    if (ValidaVacio(Elemento,campo,requerido)==false)
        return false;
            
    //Se valida de que el campo no tenga mas de 10 caracteres.
    if(Elemento.value.length > 10)
    {
        alert(campo +  " debe tener un máximo de 10 caracteres");
        return false;
    }
            
    //Se valida de que el campo no tenga menos de 5 caracteres.
    else if(Elemento.value.length < 5)
    {
        alert(campo + " debe tener un mínimo de 5 caracteres");
        return false;
    }
            
    //Se valida que la clave nueva con la repeticion de clave sean las mismas
    if(document.getElementById('CLAVENEW').value != document.getElementById('CLAVEOLD').value)
    {
        alert("Confirmacion de clave no coincide");
        return false;
    }          
}
function ValidaMaximoMinimo(Elemento, campo, requerido)
{
    if (ValidaVacio(Elemento,campo,requerido)==false)
        return false;
            
    //Se valida de que el campo no tenga mas de 10 caracteres.
    if(Elemento.value.length > 10)
    {
        alert(campo +  " debe tener un máximo de 10 caracteres");
        return false;
    }
            
    //Se valida de que el campo no tenga menos de 5 caracteres.
    else if(Elemento.value.length < 5)
    {
        alert(campo + " debe tener un mínimo de 5 caracteres");
        return false;
    }
           
}

function ValidaAlfaNumericoMALO(Elemento, campo, requerido)
{
    /*if(/[^a-zA-Z0-9]/.test(document.getElementById('CLAVENEW').value || document.getElementById('CLAVEOLD').value || Elemento.value!="" )){ // solo numeros letras espacios
        
        alert("Debe ingresar solo letras o numeros en Clave Nueva y/o Repetir Clave");
        return false;
    }
    else{
        return true;
    }*/
}


function ValidaAlfaNumerico(Elemento,campo,requerido)
{
    if (ValidaVacio(Elemento,campo,requerido)==false)
        return false;
    if (Elemento.value!="")
    {
        var exprecion=/^([A-Z??a-z??0-9\s])*/;
                        
        if (exprecion.test(Elemento.value))
        {
            return true;
        }   
        else
        {
            alert("Debe ingresar solo letras o números en: " + campo)
            Elemento.focus();
            Elemento.select();
            return false;
        }
    }
    return true;
}

function ValidaIp (Elemento,campo,requerido )
{
   
    if (ValidaVacio(Elemento,campo,requerido)==false)
        return false;
    else {
        if(Elemento.value == '')
        return true;
    }
    //Creamos un objeto
    //object=document.getElementById(idForm);
    //valueForm=object.value;
    valueForm = Elemento.value;
    // Patron para la ip
    var patronIp=new RegExp("^([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3})$");
    //window.alert(valueForm.search(patronIp));
    // Si la ip consta de 4 pares de números de máximo 3 dígitos
    if(valueForm.search(patronIp)==0)
    {
        // Validamos si los números no son superiores al valor 255
        valores=valueForm.split(".");
        if(valores[0]<=255 && valores[1]<=255 && valores[2]<=255 && valores[3]<=255)
        {
            //Ip correcta
            //object.style.color="#000";
            return true;
        } else {
            alert("Debe ingresar una IP Correcta: " + campo)
            Elemento.focus();
            Elemento.select();
            return false;
        }
    } else {
            alert("Debe ingresar una IP Correcta: " + campo)
            Elemento.focus();
            Elemento.select();
            return false;
    }
    return true;
    //object.style.color="#f00";
}


function ValidaFecha(Elemento,campo,requerido)
{
	if (ValidaVacio(Elemento,campo,requerido)==false)
        return false;
    if (Elemento.value!="")
    {
    	  	if(!existeFecha(Elemento.value))
    	  	{
    	  		alert('Fecha Incorrecta');
    	  		Elemento.focus();
                Elemento.select();
    	  		return false;
    	  	}
    	  	
        exprecion=/^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
        if (exprecion.test(Elemento.value))
            return true;
        else
        {
            alert('Debe ingresar una fecha en ' + campo + '\r Ej: xx/xx/xxxx');
            Elemento.focus();
            Elemento.select();
            return false;
        }
    }
    
	return false;
}


function ValidaConfirmacionClave(Elemento, campo, requerido)
{
    if (ValidaVacio(Elemento,campo,requerido)==false)
        return false;
    if(input.CLAVENEW.form != input.CLAVEOLD.form) 
    {
        alert('Nueva clave y confirmación no son iguales');
        Elemento.focus();
        return false;
    }
               
    return true;
}



function ValidaHora(Elemento,campo,requerido)
{
    if (ValidaVacio(Elemento,campo,requerido)==false)
        return false;
    if (Elemento.value!="")
    {
        exprecion=/^(0[1-9]|1\d|2[0-3]):([0-5]\d)$/;
        if (exprecion.test(Elemento.value))
            return true
        else
        {
            alert('Debe ingresar una hora en ' + campo + '\r Ej: xx:xx');
            Elemento.focus();
            Elemento.select();
            return false;
        }
    }
    return true;
}
function ValidaTelefono(Elemento,campo,requerido)
{
    if (ValidaVacio(Elemento,campo,requerido)==false)
        return false;
    if (Elemento.value!="")
    {
        exprecion=/^[0-9]{2,3}-[0-9]{6,7}$/;
        if (exprecion.test(Elemento.value))
            return true
        else
        {
            alert('Debe ingresar un número telefónico en ' + campo + '\r Ej: xx-xxxxxxx');
            Elemento.focus();
            Elemento.select();
            return false;
        }
    }
    return true;
}

function ValidaTelefonoSinFormato(Elemento,campo,requerido)
{
//alert(1);
    if (ValidaVacio(Elemento,campo,requerido)==false)
        return false;
    if(Elemento.value.length >9 ||Elemento.value.length<9 )
	{
	   alert("Debe ingresar un número de telefono válido (9 digitos)");
	   Elemento.focus();
	   Elemento.select();
	   Elemento.value="";
	   return;
	}else{
    if (Elemento.value!="")
    {
        exprecion=/^([0-9\s\(\)+])*$/;
        if (exprecion.test(Elemento.value))
            return true
        else
        {
            alert("Debe ingresar solo números y parentesis " + campo)
            Elemento.focus();
            Elemento.select();
            return false;
        }
    }
    return true;
	}
}


function ValidaMail(Elemento,campo,requerido)
{
    var E=Elemento;
    if (ValidaVacio(Elemento,campo,requerido)==false)
        return false;
    if (Elemento.value!="")
    {
        exprecion=/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
        if (exprecion.test(Elemento.value))
            return true
        else
        {
            alert('Debe ingresar un correo electrónico en ' + campo + '\r Ej: usuario@dominio');
            E.focus();
            E.select();
            return false;
        }
    }
    return true;
}

function ValidaMMYYYY(Elemento,campo,requerido)
{    
	
    var E=Elemento;
    if (ValidaVacio(Elemento,campo,requerido)==false)
        return false;
    if (Elemento.value!="")
    {
			
        var reg = new RegExp("(((0[123456789]|10|11|12)" +
            "(([1][9][0-9][0-9])|([2][0-9][0-9][0-9]))))");	 
        if (reg.test(Elemento.value))
            return true
        else
        {
            alert('Debe ingresar valor en el campo ' + campo + '\rcon el siguiente formato  Ej: MMYYYY');
            E.focus();
            E.select();
            return false;
        }
    }
    return true;
}

function ValidaMMDDYYYY(Elemento,campo,requerido)
{    
	
    if (ValidaVacio(Elemento,campo,requerido)==false)
        return false;
    if (Elemento.value!="")
    {
        exprecion=/^\d{2}\-\d{2}\-\d{4}$/;
        if (exprecion.test(Elemento.value))
            return true
        else
        {
            alert('Debe ingresar una fecha en ' + campo + '\r Ej: xx-xx-xxxx');
            Elemento.focus();
            Elemento.select();
            return false;
        }
    }
    return true;
}


function ValidaRut(Elemento,campo,requerido)
{
    var E=Elemento;
    var rut=E.value;
    if (ValidaVacio(Elemento,campo,requerido)==false)
        return false;
    if (Elemento.value=="")
    {
        return true;
    }
    var tmpstr = "";
    for ( i=0; i < rut.length ; i++ )
        if ( rut.charAt(i) != ' ' && rut.charAt(i) != '.' && rut.charAt(i) != '-' )
            tmpstr = tmpstr + rut.charAt(i);
    rut = tmpstr;
    largo = rut.length;
    // [VARM+]
    tmpstr = "";
    for ( i=0; rut.charAt(i) == '0' ; i++ );
    for (; i < rut.length ; i++ )
        tmpstr = tmpstr + rut.charAt(i);
    rut = tmpstr;
    largo = rut.length;
    // [VARM-]
    if ( largo < 2 )
    {
        alert("Debe ingresar el rut completo.");
        Elemento.value = "";
        Elemento.focus();
        Elemento.select();
        return false;
    }
    for (i=0; i < largo ; i++ )
    {
        if ( rut.charAt(i) != "0" && rut.charAt(i) != "1" && rut.charAt(i) !="2" && rut.charAt(i) != "3" && rut.charAt(i) != "4" && rut.charAt(i) !="5" && rut.charAt(i) != "6" && rut.charAt(i) != "7" && rut.charAt(i) !="8" && rut.charAt(i) != "9" && rut.charAt(i) !="k" && rut.charAt(i) != "K" )
        {
            alert("El valor ingresado no corresponde a un Rut válido.");
            Elemento.value = "";
            Elemento.focus();
            Elemento.select();
            return false;
        }
    }
    var invertido = "";
    for ( i=(largo-1),j=0; i>=0; i--,j++ )
        invertido = invertido + rut.charAt(i);
    var drut = "";
    drut = drut + invertido.charAt(0);
    drut = drut + '-';
    cnt = 0;
    for ( i=1,j=2; i<largo; i++,j++ )
    {
        if ( cnt == 3 )
        {
            drut = drut + '.';
            j++;
            drut = drut + invertido.charAt(i);
            cnt = 1;
        }
        else
        {
            drut = drut + invertido.charAt(i);
            cnt++;
        }
    }
    invertido = "";
    for ( i=(drut.length-1),j=0; i>=0; i--,j++ )
        invertido = invertido + drut.charAt(i);
    Elemento.value = invertido;
    if ( checkDV(Elemento,rut,campo) )
        return true;
    return false;
}



function checkDV(Elemento, crut,campo )
{
    var E=Elemento;
    largo = crut.length;
    if ( largo < 2 )
    {
        alert("Debe ingresar el rut completo en " + campo);
        Elemento.focus();
        Elemento.select();
        return false;
    }
    if ( largo > 2 )
        rut = crut.substring(0, largo - 1);
    else
        rut = crut.charAt(0);
		
    dv = crut.charAt(largo - 1);
    checkCDV(Elemento, dv, campo );
    if ( rut == null || dv == null )
        return 0;
    var dvr = '0';
    suma = 0;
    mul = 2;
    for (i= rut.length -1 ; i >= 0; i--)
    {
        suma = suma + rut.charAt(i) * mul;
        if (mul == 7)
            mul = 2;
        else
            mul++;
    }
    res = suma % 11;
    if (res==1)
        dvr = 'k';
    else if (res==0)
        dvr = '0';
    else
    {
        dvi = 11-res;
        dvr = dvi + "";
    }
    if ( dvr != dv.toLowerCase() )
    {
        alert("El rut es incorrecto.");
        Elemento.focus();
        Elemento.value = "";
        return false;
    }
    return true;
}

function checkCDV(Elemento, dvr, campo )
{
    dv = dvr + "";
    if ( dv != '0' && dv != '1' && dv != '2' && dv != '3' && dv != '4' && dv != '5' && dv != '6' && dv != '7' && dv != '8' && dv != '9' && dv != 'k'  && dv != 'K')
    {
        alert("Debe ingresar un dígito verificador valido en "+ campo);
        Elemento.focus();
        Elemento.select();
        return false;
    }
    return true;
}

/****************************************************************/

function ValidaRut2(Elemento,campo,requerido)
{
    var E=Elemento;
    var rut=E.value;
    if (ValidaVacio(Elemento,campo,requerido)==false)
        return false;
    if (Elemento.value=="")
    {
        return true;
    }
    var tmpstr = "";
    for ( i=0; i < rut.length ; i++ )
        if ( rut.charAt(i) != ' ' && rut.charAt(i) != '.' && rut.charAt(i) != '-' )
            tmpstr = tmpstr + rut.charAt(i);
    rut = tmpstr;
    largo = rut.length;
    // [VARM+]
    tmpstr = "";
    for ( i=0; rut.charAt(i) == '0' ; i++ );
    for (; i < rut.length ; i++ )
        tmpstr = tmpstr + rut.charAt(i);
    rut = tmpstr;
    largo = rut.length;
    // [VARM-]
    if ( largo < 2 )
    {
        alert("Debe ingresar el folio completo.");
        Elemento.focus();
        Elemento.select();
        return false;
    }
    for (i=0; i < largo ; i++ )
    {
        if ( rut.charAt(i) != "0" && rut.charAt(i) != "1" && rut.charAt(i) !="2" && rut.charAt(i) != "3" && rut.charAt(i) != "4" && rut.charAt(i) !="5" && rut.charAt(i) != "6" && rut.charAt(i) != "7" && rut.charAt(i) !="8" && rut.charAt(i) != "9" && rut.charAt(i) !="k" && rut.charAt(i) != "K" )
        {
            alert("El valor ingresado no corresponde a un folio válido.");
            Elemento.focus();
            Elemento.select();
            return false;
        }
    }
    var invertido = "";
    for ( i=(largo-1),j=0; i>=0; i--,j++ )
        invertido = invertido + rut.charAt(i);
    var drut = "";
    drut = drut + invertido.charAt(0);
    drut = drut + '-';
    cnt = 0;
    for ( i=1,j=2; i<largo; i++,j++ )
    {
        if ( cnt == 3 )
        {
            drut = drut + '.';
            j++;
            drut = drut + invertido.charAt(i);
            cnt = 1;
        }
        else
        {
            drut = drut + invertido.charAt(i);
            cnt++;
        }
    }
    invertido = "";
    for ( i=(drut.length-1),j=0; i>=0; i--,j++ )
        invertido = invertido + drut.charAt(i);
    Elemento.value = invertido;
    if ( checkDV2(Elemento,rut,campo) )
        return true;
    return false;
}

function checkDV2(Elemento, crut,campo )
{
    var E=Elemento;
    largo = crut.length;
    if ( largo < 2 )
    {
        alert("Debe ingresar el folio completo en " + campo);
        Elemento.focus();
        Elemento.select();
        return false;
    }
    if ( largo > 2 )
        rut = crut.substring(0, largo - 1);
    else
        rut = crut.charAt(0);
		
    dv = crut.charAt(largo - 1);
    checkCDV2(Elemento, dv, campo );
    if ( rut == null || dv == null )
        return 0;
    var dvr = '0';
    suma = 0;
    mul = 2;
    for (i= rut.length -1 ; i >= 0; i--)
    {
        suma = suma + rut.charAt(i) * mul;
        if (mul == 7)
            mul = 2;
        else
            mul++;
    }
    res = suma % 11;
    if (res==1)
        dvr = 'k';
    else if (res==0)
        dvr = '0';
    else
    {
        dvi = 11-res;
        dvr = dvi + "";
    }
    if ( dvr != dv.toLowerCase() )
    {
        alert("EL folio es incorrecto.");
        Elemento.focus();
        Elemento.value = "";
        return false;
    }
    return true;
}

function checkCDV2(Elemento, dvr, campo )
{
    dv = dvr + "";
    if ( dv != '0' && dv != '1' && dv != '2' && dv != '3' && dv != '4' && dv != '5' && dv != '6' && dv != '7' && dv != '8' && dv != '9' && dv != 'k'  && dv != 'K')
    {
        alert("Debe ingresar un dígito verificador válido en "+ campo);
        Elemento.focus();
        Elemento.select();
        return false;
    }
    return true;
}

function existeFecha(fecha){
    var fechaf = fecha.split("/");
    var day = fechaf[0];
    var month = fechaf[1];
    var year = fechaf[2];
    var date = new Date(year,month,'0');
    if((day-0)>(date.getDate()-0)){
          return false;
    }
    return true;
}
function ValidaAnulacion(Elemento,campo,requerido){
    if($('select[name=txt_ESTADO]').val() == 'V'){
        return true;
    }
    if (ValidaVacio(Elemento,campo,requerido)==false)
        return false;
    if (Elemento.value!="")
    {
        var exprecion=/^([A-Z??a-z??0-9\s])*/;
                        
        if (exprecion.test(Elemento.value))
        {
            return true;
        }   
        else
        {
            alert("Debe ingresar solo letras o números en: " + campo)
            Elemento.focus();
            Elemento.select();
            return false;
        }
    }
    return true;
    
}

