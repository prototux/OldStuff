<div class="row">
    <div class="span12">
        <div class="navbar navbar-inverse" id="main-menu">
            <div class="navbar-inner">
                <div class="container">
                    <div class="nav-collapse">
                        <ul class="nav">
                            <li class="active"><a href="index"><i class="icon-home"></i></a></li>
                            <?php
                                foreach($menu as $category)
                                    echo '<li><a href="category/'.str_replace(' ', '-', $category['name']).'/'.$category['id'].'">'.$category['name'].'</a></li>';
                            ?>
                            <li class="search_form pull-right">
								<form method="post" action="search" class="navbar-search">
									<input type="text" name="search" class="input-medium search-query" placeholder="search...">
								</form>
							</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>