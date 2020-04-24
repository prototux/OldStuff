<div class="widewrapper main">
    <div class="container content">
        <h1>Nos galeries photos  <?php if (isAdmin()) echo ' | <span style="font-size: large;"><a href="addgallery.php">New gallery</a></span>'; ?></h1>

        <?php
            $i = 0;
            foreach ($galleries as $gallery)
            {
                if ($i == 0)
                    echo '<div class="row credits">';
                ?>

	                <div class="span2 image">
	                    <a href="gallery.php?id=<?php echo $gallery['id']; ?>"><img src="images/galleries/<?php echo $gallery['id']; ?>/1.jpg" alt=""></a>
                    </div>
                    <div class="span4 details">
                        <h3><a href="gallery.php?id=<?php echo $gallery['id']; ?>"><?php echo $gallery['title']; ?></a></h3>
                        <div>
                            <a href="gallery.php?id=<?php echo $gallery['id']; ?>"><?php echo $gallery['description']; ?></a>
                        </div>
                    </div>

                <?php
                if ($i++ == 1)
                    $i = 0;

                if ($i == 0)
                    echo '</div>';
            }
            if ($i == 1)
                echo '</div>';
        ?>
    </div>
</div>