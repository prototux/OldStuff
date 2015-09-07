<div class="full_page_photo" style="background-image: url(img/services.jpg);">
     <div class="container">
          <section class="call_to_action">
               <h3 class="skincolored">Des outils pour <strong>tout</strong> prototyper</h3>
               <h4>Quel objet innovant allez vous creer avec nos outils?</h4>
          </section>
     </div>
</div>
<div class="main">
     <div class="container">
          <section class="hgroup">
               <h1>Materiel</h1>
               <ul class="breadcrumb pull-right">
                    <li><a href="index.php"><span class="fa fa-home"></span></a> </li>
                    <li class="active">Materiel</li>
               </ul>
          </section>
          <section class="service_teasers">
               <?php
                    $i = 0;
                    foreach ($tools as $tool) {
                         if ($i%2) {
               ?>
                    <div class="service_teaser right">
                         <div class="row">
                              <div class="service_details col-sm-8 col-md-8">
                                   <h2 class="section_header skincolored"><?php echo $tool['name']; ?><small><?php echo $tool['smalldesc']; ?></small></h2>
                                   <p><?php echo $tool['description']; ?></p>
                              </div>
                              <div class="service_photo col-sm-4 col-md-4">
                                   <figure style="background-image:url(img/tools/<?php echo $tool['id']; ?>.jpg)"></figure>
                              </div>
                         </div>
                    </div>
               <?php } else { ?>
                    <div class="service_teaser">
                         <div class="row">
                              <div class="service_photo col-sm-4 col-md-4">
                                   <figure style="background-image:url(img/tools/<?php echo $tool['id']; ?>.jpg)"></figure>
                              </div>
                              <div class="service_details col-sm-8 col-md-8">
                                   <h2 class="section_header skincolored"><?php echo $tool['name']; ?><small><?php echo $tool['smalldesc']; ?></small></h2>
                                   <p><?php echo $tool['description']; ?></p>
                              </div>
                         </div>
                    </div>
               <?php } $i++; } ?>

          </section>
     </div>
<script>$('body').addClass('collapsing_header');</script>
