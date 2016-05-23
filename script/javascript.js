var miformulario = document.forms[0];
    
function check(campo) {
    valor = miformulario.campo.value;
      if (valor === null || valor.length === 0 || /^\s+$/.test(valor) ) {
          alert("Error en campo de formulario: " + campo);
          miformulario.campo.focus();
          return false;
      }
      
    return true;  
}

function validar() {
    elem = miformulario.elements;
    for (var i = 0; i < elem.length; i++) {
        if (elem[i].type === "text") {
            check(elem[i].name);
        }
    } 
    return true;
};

// Cabeceras y pies de página 

document.getElementById("foot01").innerHTML =
"<p><em>" + new Date().getFullYear() + " eduGraph! creado por Aitor Igartua" +
"Gutiérrez usando la librería <a href='http://www.pchart.net/' target='_blank'>" +
"pChart</a></em></p>";
    
$(document).ready(function(){
  $("#toplogo").load("/eduGraph/toplogo.php"); 
});        
    
$(document).ready(function(){
  $("#nav01").load("/eduGraph/nav01.php"); 
});        
      
    
    