<div class="widewrapper main">
    <div class="container">
        <div class="row">
            <div class="span10 blog-main">
                <h1>Login to view private articles and galleries</h1>
                <aside class="create-comment" id="create-comment">
                    <form action="private.php" method="post" accept-charset="utf-8">
                        <?php if ($passwordFailed) echo '<div class="controls controls-row"><p>Password fail!</p></div>'; ?>
                        <div class="controls controls-row">
                            <input type="password" name="password" id="password" placeholder="Password" class="span10">
                        </div>
                        <div class="buttons clearfix">
                            <p>You will be redirected to the main page, after logon.</p>
                            <button type="submit" class="btn btn-xlarge btn-tales-one">Login</button>
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