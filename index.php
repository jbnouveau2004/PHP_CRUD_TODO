<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php
//-------------------CONNEXION BDD-------------------------------------
$chaine_connexion="mysql:host=localhost;dbname=test_crud";
$login="root";
$pass="";

try{ // à tester
    $connexion=new PDO($chaine_connexion, $login, $pass);
}
catch (Exception $e) // si erreur
{
    echo "erreur: " . $e->getMessage() . ""; // écrit le message d'erreur retourné
    die;
}


//------------------------ AJOUT D'UNE TACHE---------------------------------
if(isset($_POST['ajouter'])){
    $requete = "INSERT INTO tableau (nom, date, tache, valide) VALUES ('" . htmlentities($_POST['nom'])  . "', '" . $_POST['date']  . "', '" . htmlentities($_POST['tache'])  . "', false)"; // choix requête
    $resultats = $connexion->query($requete); // on envoit la requête 
}


//-----------------MODIFIE LE STATUT DE LA TACHE (effectué / non effectué)----------------
if(isset($_POST['effectue'])){
    if($_GET['valide']=="1"){
    $requete = "UPDATE tableau SET valide=1 WHERE id=" . $_GET['id'] . ""; // choix requête
    }else{
        $requete = "UPDATE tableau SET valide=0 WHERE id=" . $_GET['id'] . ""; // choix requête    
    }
    $resultats = $connexion->query($requete); // on envoit la requête 
}

if(isset($_POST['supprimer'])){
        $requete = "DELETE FROM tableau WHERE id=" . $_GET['id'] . ""; // choix requête    
        $resultats = $connexion->query($requete); // on envoit la requête 
}


//----------------------- APPEL A LA MODIFICATION----------------------------------
if(isset($_POST['modifier'])){
    $requete = "SELECT * FROM tableau WHERE id=" . $_GET['id'] . ""; // choix requête    
    $resultats = $connexion->query($requete); // on envoit la requête 
    foreach($resultats as $ligne){

    }
    echo ' 
    <form action="index.php" method="POST">
    <input type="hidden" name="id" id="id" value="' . $ligne['id'] . '">
    <input type="text" name="nom" id="nom" placeholder="Entrez le nom de la tâche" size=30 value="' . $ligne['nom'] . '">
    <input type="date" name="date" id="date" value="' . $ligne['date'] . '">
    <input type="text" name="tache" id="tache" placeholder="Entrez la tâche à réaliser" size=60 value="' . $ligne['tache'] . '">
    <input type="submit" name="modification" id="modification" value="Modifer cette tâche">
    </form>
    ';
}

//-------------------------------------- MODIFIER -------------------------
if(isset($_POST['modification'])){
    $requete = "UPDATE tableau SET nom='" . htmlentities($_POST['nom']) . "', date='" . $_POST['date'] . "', tache='" . htmlentities($_POST['tache']) . "' WHERE id=" . $_POST['id'] . ""; // choix requête   
    $resultats = $connexion->query($requete); // on envoit la requête 
}

?>

<!---------------------------------Affichage -------------------------------------->

<form action="index.php" method="POST">
    <input type="text" name="nom" id="nom" placeholder="Entrez le nom de la tâche" size=30>
    <input type="date" name="date" id="date">
    <input type="text" name="tache" id="tache" placeholder="Entrez la tâche à réaliser" size=60>
    <input type="submit" name="ajouter" id="ajouter" value="Ajouter cette tâche">
</form>

<hr>

<h1>Tâches à effectuer</h1>

<table id="todo">
    <tr><th width="25px" align="left">id</th><th width="250px" align="left">Nom</th><th width="100px" align="left">Date</th><th width="500px" align="left">Tâches à réaliser</th><th></th><th></th><th></th></tr>
<?php
$requete = "SELECT * FROM tableau WHERE valide=false"; // choix requête
$resultats = $connexion->query($requete); // on envoit la requête

foreach($resultats as $ligne){
    echo "<tr><td>" . $ligne['id'] . "</td><td>" . $ligne['nom'] . "</td><td>" . $ligne['date'] . "</td><td>" . $ligne['tache'] . "</td>";
    echo "<td><form action='index.php?modifier=&id=" . $ligne['id']  . "' method='POST'><input type='submit' name='modifier' id='modifier' value='Modifier'></form></td>";
    echo "<td><form action='index.php?supprimer=&id=" . $ligne['id']  . "' method='POST'><input type='submit' name='supprimer' id='supprimer' value='Supprimer'></form></td>";
    echo "<td><form action='index.php?valide=1&id=" . $ligne['id']  . "' method='POST'><input type='submit' src='oui.png' name='effectue' id='effectue' value='Effectué'></form></td></tr>";
}
?>
</table>

<hr>

<h1>Tâches effectuées</h1>

<table id="done">
    <tr><th width="25px" align="left">id</th><th width="250px" align="left">Nom</th><th width="100px" align="left">Date</th><th width="500px" align="left">Tâches réalisée</th><th></th><th></th><th></th></tr>
    <?php
$requete = "SELECT * FROM tableau WHERE valide=true"; // choix requête
$resultats = $connexion->query($requete); // on envoit la requête

foreach($resultats as $ligne){
    echo "<tr><td>" . $ligne['id'] . "</td><td>" . $ligne['nom'] . "</td><td>" . $ligne['date'] . "</td><td>" . $ligne['tache'] . "</td>";
    echo "<td><form action='index.php?modifier=&id=" . $ligne['id']  . "' method='POST'><input type='submit' name='modifier' id='modifier' value='Modifier'></form></td>";
    echo "<td><form action='index.php?supprimer=&id=" . $ligne['id']  . "' method='POST'><input type='submit' name='supprimer' id='supprimer' value='Supprimer'></form></td>";
    echo "<td><form action='index.php?valide=0&id=" . $ligne['id']  . "' method='POST'><input type='submit' src='non.png' name='effectue' id='pas_effectue' value='Pas effectué'></form></td></tr>";
}
?>
</table>

</body>
</html>