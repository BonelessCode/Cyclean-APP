<?php session_start(); 

if (empty($_SESSION['id'])){
    header('Location: page_accueil_visiteur.php');
}
?>

<!DOCTYPE html>

<html lang="fr">
    <head>
        <meta charset="UTF-8" />
        <title>Cyclean - Profil</title>
        <link rel="stylesheet" href="style_page_statistiques.css?v=<?php echo time(); ?>">

        <!-- Importation du fichier header-->
        <script src="jquery.js"></script>
        <script> 
            $(function(){
                $("#header").load("contenu/header.php"); 
            });
        </script> 

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>


    <body>

        <?php include 'database.php';
            global $db;

            // CHOPE L'ID du lien, VOIR PAGE NOTIFICATIONS
            $id_actuel=$_GET['id'];
            $q = $db ->prepare("SELECT pseudo,Nom,prenom,Extension,Apropos FROM utilisateurs WHERE id = ?");
            $q ->execute([$id_actuel]);
            $information_utilisateur = $q->fetch();


            // Si l'utilisateur existe, execute la requete dans la BDD.
            if($information_utilisateur){




                
                    // Fonction qui a un objet de type Array en PHP renvoie une liste JAVASCRIPT
                    function ArrayToJavascript($Data){
                        $DataStr = '[';

                        for($i = 0; $i < count($Data); $i++){

                            $DataStr = $DataStr."'".$Data[$i]."'";

                            if($i != count($Data)-1){
                                $DataStr = $DataStr.',';
                            }
                        }
                        $DataStr = $DataStr.']';
                        return $DataStr;
                    }







                    // Récupère le numéro du dernier trajet de l'utilisateur
                    $q = $db->prepare("SELECT MAX(NumSerie) FROM mesures WHERE Id = :Id");

                    $q -> execute(['Id' => $id_actuel]);

                    $dernierTrajetArray = $q->fetch(); // Convertit le résultat en un tableau et le paramètre PDO enlève les doublons 
                    $dernierTrajet = $dernierTrajetArray[0];
                    global $dernierTrajet;

                    // Si on a fait un trajet auparavant
                    if($dernierTrajet!=null){
                    
                        // Récupère Mesures FREQUENCIELLES du dernier trajet
                        $q_DTrajetF = $db->prepare("SELECT ValeurMesure from mesures WHERE Id = :id AND TypeCapteur = :capteur AND NumSerie = :numderniertrajet");
                        $q_DTrajetF -> execute(['id' => $id_actuel,'capteur' => 'FrequenceC', 'numderniertrajet' => $dernierTrajet]);
                        
                        $DTrajetFArray = $q_DTrajetF->fetchAll();

                        $DTrajetF = $DTrajetFArray[0];


                        // Création Liste contenant uniquement les données
                        $DataDTrajetFArray = [];

                        for($i = 0; $i < count($DTrajetFArray); $i++){
                            array_push($DataDTrajetFArray, $DTrajetFArray[$i][0]);
                        }

                        $DataDTrajetFArray = array_filter($DataDTrajetFArray);            


                    
                        // Calcul de la moyenne de cette liste de données
                        $moyenneFreqc = array_sum($DataDTrajetFArray)/count($DataDTrajetFArray);
                        $moyenneFreqc =ceil($moyenneFreqc);














                        // Récupère Mesures SONORES fréquence du dernier trajet
                        $q_DTrajetS = $db->prepare("SELECT ValeurMesure from mesures WHERE Id = :id AND TypeCapteur = :capteur AND NumSerie = :numderniertrajet");
                        $q_DTrajetS -> execute(['id' => $id_actuel,'capteur' => 'Sonore', 'numderniertrajet' => $dernierTrajet]);
                        
                        $DTrajetSArray = $q_DTrajetS->fetchAll();
                        $DTrajetS = $DTrajetSArray[0];






                        // Création Liste contenant uniquement les données concernées
                        $DataDTrajetSArray = [];
                        for($i = 0; $i < count($DTrajetSArray); $i++){
                            array_push($DataDTrajetSArray, $DTrajetSArray[$i][0]);
                        }

                        $DataDTrajetSArray = array_filter($DataDTrajetSArray);                



                        // Calcul de la moyenne de cette liste de données
                        $moyenneS = array_sum($DataDTrajetSArray)/count($DataDTrajetSArray);
                        $moyenneS = ceil($moyenneS);

                    













                    

                        // Recherche des MOYENNES DU MOIS

                        // On commence par définir le mois précédent
                        $endDate = date('Y-m-d H:i:s');

                        $startDate = date("Y-m-d H:i:s",strtotime("-1 month"));




                        // Moyenne en fréquence cardiaque
                        $q_moisyenneF = $db->prepare("SELECT ROUND(AVG(ValeurMesure),0) FROM mesures WHERE Id = ? AND TypeCapteur = ? AND DateMesure BETWEEN ? AND ?");

                        $q_moisyenneF -> execute([$id_actuel,'FrequenceC',$startDate, $endDate]);


                        $moisyenneFArray = $q_moisyenneF->fetch(); //Convertit le résultat en une liste
                        $moisyenneF = $moisyenneFArray[0];


                    
                    
                        // Moyenne en fréquence sonore
                        $q_moisyenneS = $db->prepare("SELECT ROUND(AVG(ValeurMesure),0) FROM mesures WHERE Id = ? AND TypeCapteur = ? AND DateMesure BETWEEN ? AND ?");

                        $q_moisyenneS -> execute([$id_actuel,'Sonore',$startDate, $endDate]);


                        $moisyenneSArray = $q_moisyenneS->fetch(); //Convertit le résultat en une liste
                        $moisyenneS = $moisyenneSArray[0];















                        // Recherche des moyennes de l'année

                        // On commence par définir l'année précédente
                        $endDateY = date('Y-m-d H:i:s');

                        $startDateY = date("Y-m-d H:i:s",strtotime("-1 year"));



                        // Moyenne de l'année en FREQUENCE CARDIAQUE
                        $q_YmoyenneF = $db->prepare("SELECT ROUND(AVG(ValeurMesure),0) FROM mesures WHERE Id = ? AND TypeCapteur = ? AND DateMesure BETWEEN ? AND ?");

                        $q_YmoyenneF -> execute([$id_actuel,'FrequenceC',$startDateY, $endDateY]);


                        $YmoyenneFArray = $q_YmoyenneF->fetch(); //Convertit le résultat en une liste
                        $YmoyenneF = $YmoyenneFArray[0];



                        // Moyenne de l'année en intensité SONORE
                        $q_YmoyenneS = $db->prepare("SELECT ROUND(AVG(ValeurMesure),0) FROM mesures WHERE Id = ? AND TypeCapteur = ? AND DateMesure BETWEEN ? AND ?");

                        $q_YmoyenneS -> execute([$id_actuel,'Sonore',$startDateY, $endDateY]);


                        $YmoyenneSArray = $q_YmoyenneS->fetch(); //Convertit le résultat en une liste
                        $YmoyenneS = $YmoyenneSArray[0];

                    }
                }
                ?>


                <header id="header"></header>


                <?php             
                // Si l'utilisateur existe, continue et  affiche la page.
                if($information_utilisateur){?>



                    <section class="bloc_marron space"> 
                    
                        <h1 class="slogan_pourc padd_left">
                            <?php 
                            if($id_actuel==$_SESSION['id']){
                                echo "Mon profil";}
                            else{
                                echo "Profil de ".$information_utilisateur[0];
                            }
                                ?>
                            </h1>

                        
                        <img src="images/Stats.png" class="imgtop" style="margin-right: 150px;" width="500px">

                        
                    <div class="boite_utilisateur1">
                    </section>



                    
                    <hr class="cyclean-trait" style="margin-bottom:200px;">



                    <!-- LES STATS -->
                    <!-- ------------------------------------------------------------ -->


                    <section class="container-flex v_center_align" style="flex-basis:100%;justify-content:space-around;margin-bottom:150px;">

                        <img src="<?php require_once('photo_profil.php'); 
                        echo get_pdp($id_actuel,$information_utilisateur['Extension']);?>" class="Image_Profil1 vertical" style="margin-left:2%;">





                        <div class="text-center" style="justify-content:space-around;">
                            <div style="padding-bottom:80px;">
                                <h1 class="slogan_pourc"><?= $information_utilisateur['pseudo']?><br></h1>

                                <h2 class="titre2"><?= $information_utilisateur['prenom']?> <?= $information_utilisateur['Nom']?></h2>
                            </div>

                            <h2 class="titre2" style="margin:0;padding:0;">A Propos de moi:</h2>
                            <p style="text-align:center;margin:0;padding:0;" ><br>
                            <?php 
                                // Ajoute des retours à la ligne automatiques et nl2br ceux de base.
                                $newtext = wordwrap(nl2br($information_utilisateur['Apropos']), 60, "<br />\n",true);
                                echo $newtext;
                            ?></p>

                        </div>

                        


                    </section>

                    <hr class="cyclean-trait" style="margin-bottom:400px;">





















                    <h1 class="slogan_pourc" style="padding-bottom: 2.5%;padding-left: 3.2%;padding-right: 2%;">
                            Dernier Trajet<br>
                        </h1>
                    
                    <hr class="cyclean-trait" style="margin-bottom:50px;">


                    <?php 
                    if($dernierTrajet!=null){?>


                        <section class="grid center" style="padding-bottom: 300px;"> 

                            <!-- Ligne 1 -->

                            <div class="graph">





                                <canvas id="myChartSonore"></canvas>


                                <script>
                                    const labels = <?= ArrayToJavascript(range(1,count($DataDTrajetSArray)));?>; // Transforme l'array php en array javascript

                                    const data = {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Intensité sonore en fonction du "temps"',
                                        backgroundColor: 'rgb(158, 133, 133)',
                                        borderColor: 'rgb(158, 133, 133)',
                                        data: <?= ArrayToJavascript($DataDTrajetSArray);?>, // Données sur les mesures du dernier trajet
                                    }]
                                    };

                                    const config = {
                                    type: 'line',
                                    data: data,
                                    options: {}
                                    };

                                    const myChartSonore = new Chart(
                                        document.getElementById('myChartSonore'),
                                        config
                                    );

                                </script>
                            </div>
                            











                            <div class="vline margin-sides"></div>
                            <hr class="special-cyclean-trait">


                            <div class="special-margin">  <!--v_center_align avant-->
                                <div class="container-flex v_center_align ">
                                    <h1 class="stats text-center"><?= $moyenneS ?></h1> 
                                    <h1 class="slogan text-center">db</h1>
                                </div>
                                
                                <h1 class="titre2 text-center">Durant votre dernier trajet,<br> vous avez subit <?= $moyenneS ?> db en moyenne.</h1>

                            </div>










                            <!-- Ligne 2 -->
                            
                            <div > <!--v_center_align avant-->
                                <h1 class="stats">85</h1>
                                <h1 class="titre2 text-center">Polluant principal<br>Pm 2,5</h1>
                            </div>
                            
                            <div class="vline margin-sides"></div>
                            <hr class="special-cyclean-trait">
                            
                            <div class="container-flex special-margin">
                                <img src="images/Plan_travail.png" width="300px">  
                            </div>


                            











                            <!-- Ligne 3 -->
                            <div class="graph">
                            <!-- Graphe qui contient les données de fréquence cardiaque du dernier trajet -->


                                <canvas id="myChartFreq"></canvas>

                                <script>

                                    const labels2 = <?= ArrayToJavascript(range(1,count($DataDTrajetFArray))); ?>; // Transforme l'array php en array javascript
                                    
                                    const data2 = {
                                    labels: labels2,
                                    datasets: [{
                                        label: 'Fréquence cardiaque en fonction du "temps"',
                                        backgroundColor: 'rgb(158, 133, 133)',
                                        borderColor: 'rgb(158, 133, 133)',
                                        data: <?= ArrayToJavascript($DataDTrajetFArray) ?>, // Données sur les mesures du dernier trajet
                                    }]
                                    };

                                    const config2 = {
                                        type: 'line',
                                        data: data2,
                                        options: {}
                                    };

                                    const myChartFreq = new Chart(
                                        document.getElementById('myChartFreq'),
                                        config2
                                    );

                                </script>
                                
                            </div>



                            <div class="vline margin-sides"></div>
                            <hr class="special-cyclean-trait ">

                            <div class="special-margin container-flex space v_center_align">
                                
                                <img src="images/HEART.png" width="150px" style="margin-right:50px;">  

                                <div>

                                    <div class="container-flex"> <!--v_center_align avant-->
                                        <h1 class="stats text-center"><?= $moyenneFreqc ?></h1> 
                                        <h1 class="slogan text-center">bpm</h1>
                                    </div>
                                    
                                    <h1 class="titre2 text-center">Durant votre dernier trajet, votre coeur battait à<br> une fréquence moyenne de <?= $moyenneFreqc ?> bpm.</h1>

                                </div>    
                            </div>






                            




                            
                        </section>

                        <section class="container-flex" style="padding-bottom:40px;">  
                            <div class="fbasis50 moyenne-box-padd" style="background-color:#927879;">
                                <h1 class="moyenne" style="padding-bottom:60px;">Moyennes du dernier mois </h1>

                                <div class="container-flex" style="justify-content:space-around;">

                                    <div>
                                        <h1 class="slogan text-center"><?= $moisyenneS ?></h1>
                                        <h2 class="moyenne text-center">DB</h2>
                                    </div>

                                    <div>
                                    <h1 class="slogan text-center">70</h1>
                                        <h2 class="moyenne text-center">pm 2.5</h2>
                                    </div>

                                    <div>
                                    <h1 class="slogan text-center"><?= $moisyenneF ?></h1>
                                        <h2 class="moyenne text-center">BPM</h2>
                                    </div>

                                </div>

                            </div>
                            
                            <div class="fbasis50 moyenne-box-padd" style="background-color:#b69797;">
                                <h1 class="moyenne" style="padding-bottom:60px;">Moyennes de l'année </h1>
                                

                                <div class="container-flex" style="justify-content:space-around;">

                                    <div>
                                        <h1 class="slogan text-center"><?= $YmoyenneS ?></h1>
                                        <h2 class="moyenne text-center">DB</h2>
                                    </div>

                                    <div>
                                    <h1 class="slogan text-center">70</h1>
                                        <h2 class="moyenne text-center">pm 2.5</h2>
                                    </div>

                                    <div>
                                    <h1 class="slogan text-center"><?= $YmoyenneF ?></h1>
                                        <h2 class="moyenne text-center">BPM</h2>
                                    </div>

                                </div>
                                
                                
                            </div>
                        </section>









                        <div class="padd_left" style="width:20%;padding-bottom:20px;float:right;text-align:right;padding-right:5%">
                            <a href="statistiques.php?id=<?= $id_actuel ?>" style="color: white;text-decoration: none;"><h1 class="titre">Détail des trajets</h1></a>
                            <hr style="color: white;">
                        </div>
                        
                    <?php } 

                    else{?>
                    <h2 class="titre2" style="text-align:center;">Aucune activité récente</h2>

                    <?php }
                    ?>

                    <!-- ------------------------------------------------------------ -->

            <?php } 
            else{?>
            <h1 class="slogan" style="text-align:center;">
                <?php
                echo "L'utilisateur recherché n'existe pas";

            }
            ?>
            </h1>







        <!-- FOOTER -->

        <footer class="container_footer" style="clear: right">                
            <div style="padding-left: 5%;">
                <img src="images/images_footer/Marron/LogoMarron.png" width="65px"><br>

                <p class="texte_footer" style="margin-top: 0px;">
                    Cyclean 
                </p>
                
            </div>




            <p class="texte_footer">
                © GREEN SENSE 2021<br>
                ALL RIGHTS RESERVED
            </p>


                




            <div style="margin-right: 5%;">

                <p class="texte_footer" style="margin-bottom: 0px; margin-top: 0px;">
                Contacts
                </p>
                
                <div>
                    <div class="logo_insta_whatsapp">
                        <img src="images/images_footer/Marron/insta-13.png" width="20px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <img src="images/images_footer/Marron/whatsapp.png" width="20px"><br>
                        <img src="images/images_footer/Marron/twitter.png" width="20px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <img src="images/images_footer/Marron/mail.png" width="20px">
                    </div>

                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/images_footer/Marron/facebook.png " width="20px">
                </div>
            </div>

        </footer>
           
                        
</body>