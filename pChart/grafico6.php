<?php
include("class/pData.class.php");
include("class/pDraw.class.php");
include("class/pImage.class.php");
protege("jefe" || "administrador");

$g_gradient_end = $_GET['g_gradient_end'];
$g_gradient_start = $_GET['g_gradient_start'];
$g_enabled = $_GET['g_gradient_enabled'];
$g_direction = $_GET['g_gradient_direction'];

//$temp=$_SESSION['tmp'];
////$leyenda1 = $_GET['asignatura'];
//$alumnof = $_GET['alumno'];
//$asignaturas = $temp['asignatura'];
//$title = $temp['alumno'];
//$dibujo = $_GET['dibujo'];
//$p_template = $_GET['paleta'];
//$g_width = $_GET['g_width'];
////$cursoActivo = $_GET['curso'];
//$g_height = $_GET['g_height'];

function left($value,$NbChar) {
    return substr($value,0,$NbChar); 
}  
 
 function right($value,$NbChar) { 
     return substr($value,strlen($value)-$NbChar,$NbChar); 
     
}  
 
 function mid($value,$Depart,$NbChar) { 
     return substr($value,$Depart-1,$NbChar); 
} 

 function extractColors($Hexa) {
   if ( strlen($Hexa) != 6 ) { 
       return(array(0,0,0)); 
   }

   $R = hexdec(left($Hexa,2));
   $G = hexdec(mid($Hexa,3,2));
   $B = hexdec(right($Hexa,2));

   return(array($R,$G,$B));
}

$myData = new pData();
$myData->addPoints(array(47,21,9,-43,-46,-19,-43,-27),"Serie1");
$myData->setSerieDescription("Serie1","Serie 1");
$myData->setSerieOnAxis("Serie1",0);

$myData->addPoints(array(-42,-37,-18,-20,-48,27,50,36),"Serie2");
$myData->setSerieDescription("Serie2","Serie 2");
$myData->setSerieOnAxis("Serie2",0);

$myData->addPoints(array(7,-30,-18,29,-15,27,-11,41),"Serie3");
$myData->setSerieDescription("Serie3","Serie 3");
$myData->setSerieOnAxis("Serie3",0);

$myData->addPoints(array("January","February","March","April","May","June","July","August"),"Absissa");
$myData->setAbscissa("Absissa");

$myData->setAxisPosition(0,AXIS_POSITION_LEFT);
$myData->setAxisName(0,"1st axis");
$myData->setAxisUnit(0,"");

$myPicture = new pImage(700,230,$myData);
$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
$myPicture->drawFilledRectangle(0,0,700,230,$Settings);

//if ($g_enabled === "on") {
list($StartR,$StartG,$StartB) = extractColors("'$g_gradient_start'");
list($EndR,$EndG,$EndB)       = extractColors("'$g_gradient_end'");
$Settings = array("StartR"=>$StartR, "StartG"=>$StartG, "StartB"=>$StartB, "EndR"=>$EndR, "EndG"=>$EndG, "EndB"=>$EndB, "Alpha"=>50);
//$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings);
//if ( $g_direction === "vertical" ) {
      
//     } 
//     else {
//      $myPicture->drawGradientArea(0,0,700,230,DIRECTION_HORIZONTAL,$Settings);
//     }
//}


$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings);
$myPicture->drawRectangle(0,0,699,229,array("R"=>0,"G"=>0,"B"=>0));

$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>20));

$myPicture->setFontProperties(array("FontName"=>"fonts/Forgotte.ttf","FontSize"=>14));
$TextSettings = array("Align"=>TEXT_ALIGN_MIDDLEMIDDLE
, "R"=>255, "G"=>255, "B"=>255);
$myPicture->drawText(350,25,"My first pChart project",$TextSettings);

$myPicture->setShadow(FALSE);
$myPicture->setGraphArea(50,50,675,190);
$myPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>"fonts/pf_arma_five.ttf","FontSize"=>6));

$Settings = array("Pos"=>SCALE_POS_LEFTRIGHT
, "Mode"=>SCALE_MODE_FLOATING
, "LabelingMethod"=>LABELING_ALL
, "GridR"=>255, "GridG"=>255, "GridB"=>255, "GridAlpha"=>50, "TickR"=>0, "TickG"=>0, "TickB"=>0, "TickAlpha"=>50, "LabelRotation"=>0, "CycleBackground"=>1, "DrawXLines"=>1, "DrawSubTicks"=>1, "SubTickR"=>255, "SubTickG"=>0, "SubTickB"=>0, "SubTickAlpha"=>50, "DrawYLines"=>ALL);
$myPicture->drawScale($Settings);

$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>10));

$Config = "";
$myPicture->drawSplineChart($Config);

$Config = array("FontR"=>0, "FontG"=>0, "FontB"=>0, "FontName"=>"fonts/pf_arma_five.ttf", "FontSize"=>6, "Margin"=>6, "Alpha"=>30, "BoxSize"=>5, "Style"=>LEGEND_NOBORDER
, "Mode"=>LEGEND_HORIZONTAL
);
$myPicture->drawLegend(563,16,$Config);

$myPicture->stroke();
?>