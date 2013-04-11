<?php 
// create handle for new PDF document 
$pdf = pdf_new(); 
// open a file 
pdf_open_file($pdf, "test.pdf"); 
// start a new page (A4) 
pdf_begin_page($pdf, 595, 842); 
// get and use a font object 
$arial = pdf_findfont($pdf, "Arial", "host", 1); pdf_setfont($pdf, $arial, 10); 
// print text 
pdf_show_xy($pdf, "There are more things in heaven and earth, Horatio,",50, 750); 
pdf_show_xy($pdf, "than are dreamt of in your philosophy", 50,730); 
// end page 
pdf_end_page($pdf); 
// close and save file 
pdf_close($pdf); 
?>
