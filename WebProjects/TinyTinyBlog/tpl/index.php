<div class="widewrapper main">
    <div class="container">
        <div class="row">
            <div class="span10 blog-main">
                <?php
                    $i = 0;
                    foreach($articles as $article)
                    {
                        if ($i == 0)
                            echo '<div class="row">';
                        ?>
                            <article class="span5 blog-teaser">
                                <header>
                                    <img src="images/articles/<?php echo $article['id']; ?>-mini.jpg" alt="">
                                    <h3><a href="article.php?id=<?php echo $article['id']; ?>"><?php echo $article['title']; ?></a></h3>
                                    <span class="meta"><?php echo $article['date']; ?>, <?php echo $article['category']; ?></span>
                                    <hr>
                                </header>
                                <div class="body">
                                    <?php echo $article['description']; ?>
                                </div>
                                <div class="clearfix">
                                    <a href="article.php?id=<?php echo $article['id']; ?>" class="btn btn-tales-one">Read more</a>
                                </div>
                            </article>
                        <?php

                        if ($i++ == 1)
                            $i = 0;

                        if ($i == 0)
                            echo '</div>';
                    }
                    if ($i == 1)
                        echo '</div>';
                ?>
                <div class="paging">
                    <?php if (isset($npage) && $npage > 1) echo '<a href="'.$url.'?page='.($npage-1).(($_GET['category'])?('&category='.$_GET['category']):'').'" class="newer"><i class="icon-long-arrow-left"></i> Newer</a>'; ?>
                    <?php if ($npage > 1 && $npage < $maxPages) echo '<span>&bull;</span>'; ?>
                    <?php if ($npage < $maxPages) echo '<a href="'.$url.'?page='.($npage+1).(($_GET['category'])?('&category='.$_GET['category']):'').'" class="older">Older <i class="icon-long-arrow-right"></i></a>'; ?>
                </div>
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
                <?php if (isAdmin() || $admin_access) { ?>
                    <div class="aside-widget">
                        <header>
                            <h3>Admin</h3>
                        </header>
                        <div class="body">
                            <ul class="tales-list">
                                    <li><a href="addarticle.php">New article</a></li>
                                    <li><a href="addcategory.php">New category</a></li>
                                    <?php if (getVar('category')) echo '<li><a href="delcategory.php?id='.getVar('category').'">Delete category</a></li>'; ?>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
            </aside>
        </div>
    </div>
</div>
