<div class="widewrapper main">
    <div class="container">
        <div class="row">
            <div class="span10 blog-main">
                <h1>Delete an article</h1>
                <aside class="create-comment" id="create-comment">
                    <form action="delarticle.php" method="post" accept-charset="utf-8">
                        <div class="controls controls-row">
                            Confirmation?
                            <input type="hidden" name="delete" value="<?php echo getVar('id'); ?>">
                        </div>
                        <div class="buttons clearfix">
                            <button type="submit" class="btn btn-xlarge btn-tales-one">Delete</button>
                        </div>
                    </form>
                </aside>
            </div>
            <aside class="span2 blog-aside">
            <div class="aside-widget">
                <header>
                    <h3>Categories</h3>
                </header>
                <div class="body">
                    <ul class="tales-list">
                        <?php foreach ($categories as $category) { ?>
                            <li><a href="index.php?category=<?php echo $category['id']; ?>"><?php echo $category['name']; ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            </aside>
        </div>
    </div>
</div>