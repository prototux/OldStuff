<div class="main">
     <div class="container">
          <section class="hgroup">
               <h1><?php echo $page['name']; ?> (liste des modifications)</h1>
               <ul class="breadcrumb pull-right">
                    <li><a href="index.php"><span class="fa fa-home"></span></a> </li>
                    <?php if ($page['url'] == 'home'){ ?>
                         <li class="active">Wiki</li>
                    <?php } else { ?>
                         <li><a href="wiki.php">Wiki</a></li>
                         <li class="active"><?php echo $page['name']; ?></li>
                    <?php } ?>
               </ul>
          </section>
          <section class="article-text">
               <table class="table table-bordered table-striped">
                    <thead>
                         <tr>
                              <th>Timestamp</th>
                              <th>Ip</th>
                              <th>Description</th>
                              <th>Actions</th>
                         </tr>
                    </thead>
                    <tbody>
                         <?php foreach ($edits AS $edit) { ?>
                         <tr>
                              <td><?php echo $edit['timestamp'].' ('.date('d-m-Y', $edit['timestamp']).')'; ?></td>
                              <td><?php echo $edit['from_ip']; ?></td>
                              <td><?php echo $edit['description']; ?></td>
                              <td><a href="wiki.php?revert=<?php echo $edit['id']; ?>" class="btn btn-warning" title="Revenir a cette version"><span class="fa fa-level-up"></span></a></td>
                         </tr>
                         <?php } ?>
                    </tbody>
               </table>
          </section>
          <section class="hgroup">
               <ul class="wiki-categories">
                    <?php
                         foreach ($categories AS $category)
                              echo '<li><a href="wiki.php?category='.$category['url'].'">'.$category['name'].'</a></li>';
                    ?>
               </ul>
          </section>
     </div>

