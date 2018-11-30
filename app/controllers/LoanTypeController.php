<?php

class LoanTypeController extends Controller{
	
	public function renderView($page){

		$this->f3->set('template', $this->f3->get('VIEWS').$page.'.htm');
		
		echo Template::instance()->render($this->f3->get('VIEWS').'layout.htm');

	}

	// The Dropdown Menu in the Display page
	public function displayTypes(){
		$loantypes = $this->db->exec('SELECT Name FROM cashloanlist;');
		$this->f3->set('SESSION.loantypes', $loantypes);

		$this->renderView('main');
	}

	public function getAcronym($loantype){
		$loanacronym = $this->db->exec('SELECT Acronym FROM cashloanlist WHERE Name=:loantype;', array(':loantype' => $loantype));

		return $loanacronym[0]['Acronym'];
	}

	public function getRevision($loantype){
		$revisiondate = $this->db->exec('SELECT Revision_Date FROM cashloanlist WHERE Name=:loantype;', array(':loantype' => $loantype));

		return $revisiondate[0]['Revision_Date'];
	}

	public function showForms(){
		$this->renderView('forms');
	}

	public function confirmInfo(){
		$studentInfo = $this->db->exec('SELECT * FROM samplestudentdb;');

		$this->f3->set('SESSION.fullname', $studentInfo[0]['name']);
		$this->f3->set('SESSION.studentnumber', $studentInfo[0]['student_no']);
		$this->f3->set('SESSION.degree', $studentInfo[0]['degree']);
		$this->f3->set('SESSION.college', $studentInfo[0]['college']);
		$this->f3->set('SESSION.standing', $studentInfo[0]['standing']);
		$this->f3->set('SESSION.semester', $studentInfo[0]['semester']);
		$this->f3->set('SESSION.academic_year', $studentInfo[0]['academic_year']);
		$this->f3->set('SESSION.prev_loan', $studentInfo[0]['prev_loan']);
		$this->f3->set('SESSION.address_permanent', $studentInfo[0]['address_permanent']);
		$this->f3->set('SESSION.parent_name', $studentInfo[0]['parent_name']);
		$this->f3->set('SESSION.mobilenumber', $studentInfo[0]['mobile_no']);
		$this->f3->set('SESSION.upmail', $studentInfo[0]['upmail']);
		$this->f3->set('SESSION.saisid', $studentInfo[0]['saisid']);

		// var_dump($studentInfo);
		$this->renderView('confirm-info');
	}

	public function setForms(){
		$loanamountwords = $this->f3->get("POST.words");
		$loanamountnum = $this->f3->get("POST.num");
		$loanpurpose = $this->f3->get("POST.purpose");
		$date = date('Y-m-d', strtotime(str_replace('-', '/', $this->f3->get("POST.duedate"))));

		$this->f3->set('SESSION.loanwords', $loanamountwords);
		$this->f3->set('SESSION.loannum', $loanamountnum);
		$this->f3->set('SESSION.loanpurpose', $loanpurpose);
		$this->f3->set('SESSION.duedate', $date);

		$this->confirmInfo();

		// $code = $this->f3->get("POST.purpose");
		// echo $code;
		// die();
	}

	// Gets the loan type 1st Page POST
	public function setLoanType(){
		$loantype = $this->f3->get("POST.loantype");

		$this->f3->set('SESSION.loantype', $loantype);

		$loanacronym = $this->getAcronym($loantype);
		$this->f3->set('SESSION.loanacronym', $loanacronym);

		$revisiondate = $this->getRevision($loantype);
		$this->f3->set('SESSION.revisiondate', $revisiondate);

		$this->showForms();
	}

	public function infoConfirmed(){
		$reqMapper = new LoanRequestsMapper($this->db);

		$reqMapper->loan_type = $this->f3->get("SESSION.loantype");
		$reqMapper->loan_amount_words = $this->f3->get("SESSION.loanwords");
		$reqMapper->loan_amount_num = $this->f3->get("SESSION.loannum");
		$reqMapper->loan_purpose = $this->f3->get("SESSION.loanpurpose");
		$reqMapper->due_date = $this->f3->get("SESSION.duedate");
		$reqMapper->student_no = $this->f3->get("SESSION.studentnumber");
		$reqMapper->saisid = $this->f3->get("SESSION.saisid");

		$reqMapper->save();

		$this->pdfGenerator();
	}

	public function pdfGenerator(){
		$pdf = new FPDF('P', 'mm', 'A4');

		//Variables
		$loantype = $this->f3->get("SESSION.loantype");
		// $loantype = "PCIBANK-UPLB STUDENT FINANCIAL ASSISTANCE PROGRAM";
		$loanacronym = $this->getAcronym($loantype);
		// $loanacronym = "PCIB-UPLB SFAP";
		$revisiondate = $this->f3->get("SESSION.revisiondate");
		// $revisiondate = '2013-11-29';
		$loanwords = $this->f3->get("SESSION.loanwords");
		// $loanwords = 'One Hundred Seventy';
		$loannum = $this->f3->get("SESSION.loannum");
		// $loannum = 170;
		$duedate = $this->f3->get("SESSION.duedate");
		// $duedate = '2018-11-11';
		$loanpurpose = $this->f3->get("SESSION.loanpurpose");
		// $loanpurpose = "Tuition";
		$fullname = $this->f3->get("SESSION.fullname");
		// $fullname = "Andrei Josh M. Rivera";
		$studentnumber = $this->f3->get("SESSION.studentnumber");
		// $studentnumber = "2015-04264";
		$saisid = $this->f3->get("SESSION.saisid");
		// $saisid = "123456789";
		$college = $this->f3->get("SESSION.college");
		// $college = "SESAM";
		$degree = $this->f3->get("SESSION.degree");
		// $degree = "BS Electrical Engineering";
		$standing = $this->f3->get("SESSION.standing");
		// $standing = "Sophomore";
		$semester = $this->f3->get("SESSION.semester");
		$academic_year = $this->f3->get("SESSION.academic_year");
		$prev_loan = $this->f3->get("SESSION.prev_loan");
		$address_permanent = $this->f3->get("SESSION.address_permanent");
		// $address_permanent = "#13 Trinidad Ave. San Antonio Subd. Brgy. Nagkaisang Nayon, Novaliches, Quezon City";
		$parent_name = $this->f3->get("SESSION.parent_name");
		// $parent_name = "Arlene Joy C. Muñoz";
		$mobilenumber = $this->f3->get("SESSION.mobilenumber");
		// $mobilenumber = "09565210653";
		$upmail = $this->f3->get("SESSION.upmail");
		// $upmail = "amrivera3@up.edu.ph";


		$pdf->AddPage();
		// Arial bold 15
    	$pdf->SetFont('Arial','',10);
		// Move to the right
	   	

	    // Title
	    $pdf->Cell(30,8,'University of the Philippines Los Banos',0,0,'L');
	    $pdf->Cell(130);
	    // $pdf->Cell(30,10,$loantype,0,0,'C');
	    $pdf->Cell(30,8,"Application No. ________ {$prev_loan} Loaner",0,0,'R');
	    $pdf->Ln();
	    //Bold Loan Acronym Here
	    $pdf->SetFont('Arial','B',10);
	    $pdf->Cell(30,8,"{$loanacronym} Application Form",0,0,'L');
	    //Change back to normal font
	    $pdf->SetFont('Arial','',10);
	    $pdf->Cell(130);
	    $pdf->Cell(30,8,"{$semester} A.Y. {$academic_year}",0,0,'R');
	    $pdf->Ln();
	    $pdf->Cell(30,8,"Revised {$revisiondate}",0,0,'L');
	    $pdf->Cell(130);
	    $pdf->Cell(30,8,"Date: 2018-11-12",0,0,'R');
	    $pdf->Ln();
	    //Name of Loan Type in Bold
	    $pdf->SetFont('Arial','B',12);
	    $pdf->Cell(60);
	    $pdf->Cell(60,8,$loantype,0,0,'C');
	    $pdf->SetFont('Arial','B',11);
	    $pdf->Ln();
	    $pdf->Cell(65);
	    $pdf->Cell(60,8,'APPLICATION FOR LOAN',0,0,'C');
	    $pdf->Ln();
	   
	    //Variable Data
	    $pdf->SetFont('Arial','',9);
	    //Data itself in Tabular Form
	    $pdf->Cell(190,8,"Amount of Loan (in words): {$loanwords} Pesos",1,0,'L');
	    $pdf->Ln();
	    $pdf->Cell(100,8,"Purpose of Loan: {$loanpurpose}",1,0,'L');
	    $pdf->Cell(52,8,"Amount of Loan: {$loannum} PHP",1,0,'L');
	    $pdf->Cell(38,8,"Due Date: {$duedate}",1,0,'L');
	    $pdf->Ln();
	    $pdf->Cell(140,8,"Student's Full Name: {$fullname}",1,0,'L');
	   	$pdf->Cell(50,8,"Student Number: {$studentnumber}",1,0,'L');
	   	$pdf->Ln();
	   	$pdf->Cell(65,8,"SAIS ID: {$saisid}",1,0,'L');
	   	$pdf->Cell(75,8,"UP Mail: {$upmail}",1,0,'L');
	   	$pdf->Cell(50,8,"Mobile Number: {$mobilenumber}",1,0,'L');
	   	$pdf->Ln();
	   	$pdf->Cell(110,8,"Degree: {$degree}",1,0,'L');
	   	$pdf->Cell(50,8,"Classification: {$standing}",1,0,'L');
	   	$pdf->Cell(30,8,"College: {$college}",1,0,'L');
	   	$pdf->Ln();
	   	$pdf->Cell(190,8,"Permanent Address: {$address_permanent}",1,0,'L');
	   	$pdf->Ln();
	   	$pdf->Cell(190,8,"Parent's Full Name: {$parent_name}",1,0,'L');
	   	$pdf->Ln();

	   	// Academic Standing
	   	$pdf->SetFont('Arial', 'B', 11);
	   	$pdf->Rect(10,106,190,31);
	   	$pdf->Cell(65);
	   	$pdf->Cell(60,8,'CERTIFICATE OF ACADEMIC STANDING',0,0,'C');
	   	$pdf->SetFont('Arial','',8);
	   	$pdf->Ln();
	   	$pdf->Cell(50);
	   	$pdf->Cell(90,8,"This is to certify that the academic standing of Mr./Ms. {$fullname} as of {$semester} A.Y. {$academic_year} is _______________.",0,0,'C');
	   	$pdf->Ln();
	   	$pdf->Cell(20);
	   	$pdf->Cell(20,8,'Certified By:',0,0);
	   	$pdf->Cell(35);
	   	$pdf->Cell(50,8,'____________________',0,0);
	   	$pdf->Cell(20,8,'_______________',0,0);
	   	$pdf->Ln();
	   	$pdf->Cell(80);
	   	$pdf->Cell(50,8,'College Secretary',0,0);
	   	$pdf->Cell(5);
	   	$pdf->Cell(20,8,'Date',0,0);
	   	$pdf->Ln();

	   	//Promissory Note
	   	$pdf->SetFont('Arial', 'B', 11);
	   	$pdf->Rect(10,137,190,65);
	   	$pdf->Cell(65);
	   	$pdf->Cell(60,8,'PROMISSORY NOTE',0,0,'C');
	   	$pdf->SetFont('Arial','',8);
	   	$pdf->Ln();
	   	$pdf->Cell(20);
	   	$pdf->Write(8,"I promise to pay the {$loantype} the amount of {$loanwords} ({$loannum} PHP) granted to me during the{$semester} A.Y. {$academic_year} with an interest rate of 4% per annum paid on or before the due date indicated above. I understand that in case of delayed payment, I will be charged an additional interest of 8% per annum thereafter until the time I pay the loan.I agree to adhere to the deadline of payment and I understand that failure to comply shall mean that I may not be able to register in the succeeding semester nor be given clearance from the University.");
	   	$pdf->Ln();
	   	$pdf->Cell(10);
	   	$pdf->Cell(30,8,'Attested By:',0,0);
	   	$pdf->Cell(10);
	   	$pdf->Cell(30,8,'_______________________',0,0);
	   	$pdf->Cell(20);
	   	$pdf->Cell(30,8,'_______________________',0,0);
	   	$pdf->Cell(20);
	   	$pdf->Cell(30,8,'_______________________',0,1);
	   	$pdf->Cell(65);
	   	$pdf->Cell(20,8,'Parent',0,0);
	   	$pdf->Cell(17);
	   	$pdf->Cell(40,8,'UPLB Faculty/REPS/Staff',0,0);
	   	$pdf->Cell(20);
	   	$pdf->Cell(20,8,'Student',0,0);
	   	$pdf->Ln();


	   	//Committee's Action
	   	$pdf->SetFont('Arial','B',11);
	   	$pdf->Rect(10,202,190,50);
	   	$pdf->Cell(65);
	    $pdf->Cell(40,8,"COMMITTEE'S ACTION",0,0);
	    $pdf->Ln();
	    $pdf->Cell(40);
	    $pdf->Cell(5,5,'',1,0,'C');
	    $pdf->Cell(20,8,'Approved',0,0,'C');
	    $pdf->Cell(40);
	    $pdf->Cell(5,5,'',1,0,'C');
	    $pdf->Cell(20,8,'Disapproved',0,0,'C');
	    $pdf->Ln();
	    $pdf->Ln();
	    $pdf->Cell(50);
	    $pdf->Cell(30,8,'_______________________',0,0,'L');
	    $pdf->Cell(30);
	    $pdf->Cell(30,8,'_______________________',0,0,'L');
		$pdf->Ln();	    

		$pdf->Output('I', "{$fullname} Loan.pdf");

		$this->renderView('main');
	}
}