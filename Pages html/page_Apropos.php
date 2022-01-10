<!DOCTYPE html>

<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <title>Cyclean - Social</title>
    <link rel="stylesheet" href="style_page_Propos.css" />

    <!-- Importation du fichier header-->
    <script src="jquery.js"></script>
    <script>
      $(function () {
        $("#header").load("contenu/header.php");
      });
    </script>
  </head>

  <body>
    <header id="header"></header>

    <!-- Page a Propos -->
    <h1 class="titre">A Propos</h1>
    <section class="container">
      <div class="trait1"></div>
      <div class="Partie1">
        <img src="images/VELOFINAL-5.png" class="ImageVelo1" />
        <div class="Image_text">
          <p class="text1">
            Je ne sais pas vraiment quoi mettre ici mas on peut mettre des infos
            cool
          </p>
          <!--<img src="images/Social.png" class="Im1" />-->
          <div class="trait2"></div>
        </div>
      </div>

      <div class="Partie2">
        <div class="Image_text2">
          <p class="text1">
            Je ne sais pas vraiment quoi mettre ici mas on peut mettre des infos
            cool
          </p>
          <div class="trait3"></div>
        </div>

        <img src="images/VELOFINAL-5.png" class="ImageVelo2" />
      </div>
    </section>

    <!------------------ FOOTER ----------------->

    <footer class="container_footer">                
      <div style="padding-left: 5%;">
          <img src="images/images_footer/Blanc/LogoGris.png " width="65px"><br>

          <p class="texte_footer" style="margin-top: 0px;">
              Cyclean 
          </p>
        
      </div>


      <p class="texte_footer">
          © GREEN SENSE 2021<br>
          ALL RIGHTS RESERVED
      </p>


      <div style="margin-right: 5%;">

          <div class="texte_footer" style="margin-bottom: 10px; margin-top: 0px;">
          Contacts
          </div>
          
          <div>
              <div class="logo_insta_whatsapp">
                  <img src="images/images_footer/Blanc/instaF.png " width="20px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <img src="images/images_footer/Blanc/WhatsappF.png " width="20px"><br>
                  <img src="images/images_footer/Blanc/TwitterF.png " width="20px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <img src="images/images_footer/Blanc/Mail.F.png " width="20px">
              </div>

              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/images_footer/Blanc/facebookF.png " width="20px">
          </div>
      </div>

    </footer>

  </body>
</html>