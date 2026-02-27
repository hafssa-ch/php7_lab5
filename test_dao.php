<?php
declare(strict_types=1);
spl_autoload_register(function(string $class){
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Log\Logger;
use App\Database\DBConnection;
use App\Dao\FiliereDao;
use App\Dao\EtudiantDao;
use App\Entity\Filiere;
use App\Entity\Etudiant;

$logger = new Logger(__DIR__ . '/logs/pdo_errors.log');
DBConnection::init($logger);
$filiereDao = new FiliereDao($logger);
$etudiantDao = new EtudiantDao($logger);

function out(string $label, $value): void {
    $formatted = is_scalar($value)
        ? $value
        : json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    printf("%-30s : %s" . "<br>", $label, $formatted);
}

// Filiere
$f = new Filiere(null, 'PHY', 'Physique');
$idF = $filiereDao->insert($f);
out('Filiere insert id', $idF);
$foundF = $filiereDao->findById($idF);
out('Filiere findById', $foundF ? $foundF->getCode() . ' / ' . $foundF->getLibelle() : 'null');
$allF = $filiereDao->findAll();
out('Filiere findAll count', count($allF));
$f->setLibelle('Physique Fondamentale');
out('Filiere update', $filiereDao->update($f));
out('Filiere delete', $filiereDao->delete($idF));

// Etudiant 
$fInfo = $filiereDao->findById(1);
if (!$fInfo) { $tmp = new Filiere(null, 'INFO', 'Informatique'); $filiereDao->insert($tmp); $fInfo = $tmp; }
$e = new Etudiant(null, 'CNE9000', 'Martin', 'Bob', 'bob.martin@example.com', (int)$fInfo->getId());
$idE = $etudiantDao->insert($e);
out('Etudiant insert id', $idE);
$foundE = $etudiantDao->findById($idE);
out('Etudiant findById', $foundE ? $foundE->getEmail() : 'null');
$allE = $etudiantDao->findAll();
out('Etudiant findAll count', count($allE));
$e->setNom('Martin-Updated');
out('Etudiant update', $etudiantDao->update($e));
out('Etudiant delete', $etudiantDao->delete($idE));

// Provoquer une erreur (email dupliqué) pour vérifier le log
try {
    $e1 = new Etudiant(null, 'CNE8001', 'Duuup', 'A', 'ali@example.com', (int)$fInfo->getId());
    $etudiantDao->insert($e1);
    out('Duplicate email test', 'unexpected success');
} catch (\PDOException $ex) {
    out('Duplicate email test', 'OK (exception)');
}

// Transaction démo (commit)
$pdo = DBConnection::get();
try {
    $pdo->beginTransaction();
    $fT = new Filiere(null, 'CHEM', 'Physique');
    $filiereDao->insert($fT);
    $eT = new Etudiant(null, 'CNE7000', 'Chem', 'Tx', 'chem.unique@example.com', (int)$fT->getId());
    $etudiantDao->insert($eT);
    $pdo->commit();
    out('Transaction', 'COMMIT');
} catch (\PDOException $ex) {
    if ($pdo->inTransaction()) { $pdo->rollBack(); }
    out('Transaction', 'ROLLBACK: ' . $ex->getMessage());
}