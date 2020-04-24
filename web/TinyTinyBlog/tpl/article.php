<div class="widewrapper main">
    <div class="container">
        <div class="row">
            <div class="span10 blog-main">
                <article class="blog-post">
                    <header>
                        <h1><?php echo $article['title']; ?></h1>
                        <div class="lead-image">
                            <img src="images/articles/<?php echo $article['id']; ?>.jpg" alt="">
                            <div class="meta clearfix">

                                <div class="author">
                                    <i class="icon-list"></i>
                                    <span class="data"><?php echo $article['category']; ?></span>
                                </div>
                                <div class="date">
                                    <i class="icon-calendar"></i>
                                    <span class="data"><?php echo $article['date']; ?></span>
                                </div>
                                <div class="comments">
                                    <i class="icon-comments"></i>
                                    <span class="data"><a href="#comments"><?php echo $commentsCount; ?> Commentaire<?php echo ($commentsCount > 1)?'s':''; ?></a></span>
                                </div>
                            </div>
                        </div>
                    </header>
                    <div class="body">
                        <?php echo $article['content']; ?>
                    </div>
                </article>

                <aside class="comments" id="comments">
                    <hr>
                    <h2><i class="icon-comments"></i> <?php echo $commentsCount; ?> Commentaire<?php echo ($commentsCount > 1)?'s':''; ?></h2>
                    <?php foreach ($comments as $comment) { ?>
                        <article class="comment">
                            <header class="clearfix">
                                <img src="<?php echo 'http://www.gravatar.com/avatar/'.md5(strtolower(trim($comment['email']))).'?d=wavatar&r=pg'; ?>" alt="" class="avatar">
                                <div class="meta">
                                    <h3><a href="#"><?php echo $comment['nickname']; ?></a></h3>
                                    <span class="date">
                                        <?php echo $comment['date']; ?>
                                    </span>
                                    <span class="separator">
                                        -
                                    </span>
                                </div>
                            </header>
                             <div class="body">
                                <?php echo $comment['comment']; ?>
                            </div>
                        </article>
                    <?php } ?>
                </aside>

                <aside class="create-comment" id="create-comment">
                    <hr>
                    <h2><i class="icon-heart"></i> Ajouter un commentaire</h2>
                    <form action="#" method="get" accept-charset="utf-8">
                        <div class="controls controls-row">
                            <input type="text" name="name" id="comment-name" placeholder="Nickname" class="span5">
                            <input type="email" name="email" id="comment-email" placeholder="Email" class="span5">
                        </div>
                        <div class="controls">
                            <textarea rows="10" name="message" id="comment-body" placeholder="Your comment..." class="span10"></textarea>
                        </div>
                        <div class="buttons clearfix">
                            <button type="submit" class="btn btn-xlarge btn-tales-one">Comment</button>
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
                <?php if (isAdmin()) { ?>
                    <div class="aside-widget">
                        <header>
                            <h3>Admin</h3>
                        </header>
                        <div class="body">
                            <ul class="tales-list">
                                    <li><a href="editarticle.php?id=<?php echo $article['id']; ?>">Edit article</a></li>
                                    <li><a href="delarticle.php?id=<?php echo $article['id']; ?>">Delete article</a></li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
            </aside>
        </div>
    </div>
</div>