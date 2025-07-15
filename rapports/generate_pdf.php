<?php
// Chemin vers la bibliothèque TCPDF
require_once(__DIR__ . '/../tcpdf/tcpdf.php');

// Connexion à la base de données
include __DIR__ . '/../includes/db.php';

// Création du PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Informations du document
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Carnet Sanitaire Digitalisé');
$pdf->SetTitle('Rapport Carnet Sanitaire');
$pdf->SetSubject('Températures et Analyses Légionelles');
$pdf->SetKeywords('legionella, eau chaude, température, sanitaire, carnet sanitaire');

// En-tête et pied de page
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Ajout d'une page
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

// Titre
$pdf->Cell(0, 10, 'Rapport Carnet Sanitaire - Mesures ECS', 0, 1, 'C');
$pdf->Ln(10);

// Sous-titre : Températures mensuelles
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Suivi des températures mensuelles', 0, 1);
$pdf->SetFont('helvetica', '', 10);
$pdf->Ln(5);

// Tableau des températures
$pdf->MultiCell(30, 10, 'Mois', 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
$pdf->MultiCell(10, 10, 'Jan', 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
$pdf->MultiCell(10, 10, 'Fév', 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
$pdf->MultiCell(10, 10, 'Mar', 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
$pdf->MultiCell(10, 10, 'Avr', 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
$pdf->MultiCell(10, 10, 'Mai', 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
$pdf->MultiCell(10, 10, 'Jun', 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
$pdf->MultiCell(10, 10, 'Jul', 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
$pdf->MultiCell(10, 10, 'Aou', 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
$pdf->MultiCell(10, 10, 'Sep', 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
$pdf->MultiCell(10, 10, 'Oct', 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
$pdf->MultiCell(10, 10, 'Nov', 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
$pdf->MultiCell(10, 10, 'Déc', 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
$pdf->Ln();

$stmt_temp = $pdo->query("SELECT * FROM surveillance_temperature ORDER BY annee DESC LIMIT 5");
while ($row = $stmt_temp->fetch(PDO::FETCH_ASSOC)) {
    $pdf->MultiCell(30, 10, $row['mois'] . " " . $row['annee'], 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
    $pdf->MultiCell(10, 10, $row['jan'], 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    $pdf->MultiCell(10, 10, $row['fev'], 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    $pdf->MultiCell(10, 10, $row['mar'], 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    $pdf->MultiCell(10, 10, $row['avr'], 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    $pdf->MultiCell(10, 10, $row['mai'], 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    $pdf->MultiCell(10, 10, $row['jun'], 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    $pdf->MultiCell(10, 10, $row['jul'], 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    $pdf->MultiCell(10, 10, $row['aou'], 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    $pdf->MultiCell(10, 10, $row['sep'], 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    $pdf->MultiCell(10, 10, $row['oct'], 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    $pdf->MultiCell(10, 10, $row['nov'], 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    $pdf->MultiCell(10, 10, $row['dec'], 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    $pdf->Ln();
}

$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Analyses légionelles annuelles', 0, 1);
$pdf->SetFont('helvetica', '', 10);
$pdf->Ln(5);

// Tableau des analyses légionelles
$pdf->MultiCell(40, 10, 'Point de prélèvement', 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
$pdf->MultiCell(25, 10, 'Date', 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
$pdf->MultiCell(20, 10, 'Temp. (°C)', 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
$pdf->MultiCell(30, 10, 'Mode prélèvement', 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
$pdf->MultiCell(20, 10, 'Résultat (UFC/L)', 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
$pdf->MultiCell(0, 10, 'Actions', 1, 'C', 1, 1, '', '', true, 0, false, true, 10, 'M');

$stmt_leg = $pdo->query("SELECT * FROM analyse_legionelle ORDER BY date DESC LIMIT 5");

while ($row = $stmt_leg->fetch(PDO::FETCH_ASSOC)) {
    $pdf->MultiCell(40, 10, $row['point_prelevement'], 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
    $pdf->MultiCell(25, 10, date('d/m/Y', strtotime($row['date'])), 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    $pdf->MultiCell(20, 10, $row['temperature'], 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    $pdf->MultiCell(30, 10, $row['mode_prelevement'], 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    $pdf->MultiCell(20, 10, $row['resultat'], 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
    $pdf->MultiCell(0, 10, $row['action'], 1, 'L', 0, 1, '', '', true, 0, false, true, 10, 'M');
}

// Fin du document
$pdf->Output('rapport_carnet_sanitaire.pdf', 'I'); // I = Inline (affiche dans le navigateur), D = Téléchargement
exit();