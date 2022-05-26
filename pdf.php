<?php
   

    if (isset($_POST['pdf'] )&& isset($_POST['htmlData'])){
        //var_dump($_POST['htmlData']);
        require_once('tcpdf/autoload.php');
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $pdf->SetFont('dejavusans', '', 12);
        $pdf->AddPage();
        $pdf->writeHTML($_POST['htmlData'], true, false, true, false, '');
        ob_end_clean();
        $pdf->Output(); 
    }
   
    //include 'config.php';

    /*$descSK='<h1>Podis API služby</h1>
        <p>táto služba....</p>';
$descEN='<h1>Podis API služby</h1>
        <p>táto služba....</p>';*/ 


    ?>