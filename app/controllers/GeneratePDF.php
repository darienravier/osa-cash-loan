<?php

class GeneratePDF extends FPDF{
	public function generate(){
		$loantype = $this->f3->get("SESSION.loantype");
		
		$pdf = new FPDF('P', 'mm', 'A4');
		$pdf->AddPage();
		// Arial bold 15
    	$pdf->SetFont('Arial','B',12);
		// Move to the right
	   	$pdf->Cell(25);
	    // Title
	    $pdf->Cell(30,10,'University of the Philippines Los Banos',0,0,'C');
	    $pdf->Ln(10);
	    $pdf->Cell(20);
	    $pdf->Cell(30,10,$loantype,0,0,'C');

	    
		$pdf->Output('I', 'Rivera.pdf');
	}
}