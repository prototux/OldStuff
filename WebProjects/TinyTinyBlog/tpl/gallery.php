<div class="widewrapper main">
    <div class="container content">
        <h1><?php echo $gallery['title']; ?> <?php if (isAdmin()) echo '| <span style="font-size: large;"><a href="">Edit </a> // <a href="delgallery.php?id='.getVar('id').'">Delete</a></span>'; ?></h1>

        <div id="blueimp-image-carousel" class="blueimp-gallery blueimp-gallery-carousel blueimp-gallery-display blueimp-gallery-playing blueimp-gallery-controls">
            <div class="slides"></div>
            <h3 class="title"></h3>
            <a class="prev">‹</a>
            <a class="next">›</a>
            <a class="play-pause"></a>
        </div>

        <div id="links" style="display: none;">
            <?php foreach ($pictures as $picture) { ?>
                <a href="images/galleries/<?php echo $picture['gallery_id']; ?>/<?php echo $picture['number']; ?>.jpg" title="<?php echo $picture['name']; ?>">
                    <img src="images/galleries/<?php echo $picture['gallery_id']; ?>/<?php echo $picture['number']; ?>.jpg" alt="<?php echo $picture['name']; ?>">
                </a>
            <?php } ?>
        </div>
        <script src="js/blueimp-gallery.min.js"></script>
        <script>
            blueimp.Gallery(
                document.getElementById('links').getElementsByTagName('a'),
                {
                    container: '#blueimp-image-carousel',
                    carousel: true,
                    stretchImages: true,
                    startSlideshow: true,
                }
            );
        </script>
    </div>
</div>