<div class="widewrapper main">
    <div class="container">
        <div class="row span12">
            <aside>
                <h2>Edit an article</h2>
                <form action="#" method="post" accept-charset="utf-8">
                    <div class="controls controls-row">
                        <input type="text" name="title" id="title" value="<?php echo $article['title']; ?>" class="span5">

                        <span class="dropdown" style="margin-left: 5px;">
                            <a href="#" data-toggle="dropdown" class="btn btn-large dropdown-toggle" id="current-privacy"><?php echo ($article['private'])?'Private':'Public'; ?> <b class="caret"></b></a>
                            <ul class="dropdown-menu" id="privacy-change">
                                <li><a data-value="0">Public</a></li>
                                <li><a data-value="1">Private</a></li>
                            </ul>
                            <input type="hidden" id="privacy" name="privacy" value="<?php echo $article['private']; ?>" />
                        </span>

                        <span class="dropdown">
                            <a href="#" data-toggle="dropdown" class="btn btn-large dropdown-toggle" id="current-category"><?php echo $article['category']; ?> <b class="caret"></b></a>
                            <ul class="dropdown-menu" id="category-change">
                                <?php foreach($categories as $category) { ?>
                                    <li><a data-value="<?php echo $category['id']; ?>" href="#"><?php echo $category['name']; ?></a></li>
                                <? } ?>
                            </ul>
                            <input type="hidden" id="category_id" name="category_id" value="<?php echo $article['category_id']; ?>" />
                        </span>
                    </div>
                    <div class="controls controls-row" >
                        <textarea rows="5" name="description" id="description" class="span10"><?php echo $article['description']; ?></textarea>
                    </div>

                    <div class="controls">
                        <div id="toolbar" style="display: none;">
                            <span class="dropdown">
                                <a href="#" data-toggle="dropdown" class="btn btn-small dropdown-toggle"><i class="icon-font toolbar-icon"></i> <span class="current-font">Text</span> <b class="caret"></b></a>
                                <ul class="dropdown-menu" id="font-change">
                                    <li><a data-wysihtml5-command-value="div" data-wysihtml5-command="formatBlock" href="#" >Text</a></li>
                                    <li><a data-wysihtml5-command-value="h1" data-wysihtml5-command="formatBlock" href="#" >Big title</a></li>
                                    <li><a data-wysihtml5-command-value="h2" data-wysihtml5-command="formatBlock" href="#" >Medium title</a></li>
                                    <li><a data-wysihtml5-command-value="h3" data-wysihtml5-command="formatBlock" href="#" >Small title</a></li>
                                </ul>
                            </span>

                            <span class="dropdown">
                                <a href="#" data-toggle="dropdown" class="btn dropdown-toggle btn-small"><i class="icon-tint" id="color-tint"></i> <span class="current-color">Black</span> <b class="caret"></b></a>
                                <ul class="dropdown-menu" id="color-change">
                                    <li><div data-wysihtml5-command-value="black" class="wysihtml5-colors"></div><a data-wysihtml5-command-value="black" data-wysihtml5-command="foreColor" class="wysihtml5-colors-title" href="#" data-color="black">Black</a></li>
                                    <li><div data-wysihtml5-command-value="silver" class="wysihtml5-colors"></div><a data-wysihtml5-command-value="silver" data-wysihtml5-command="foreColor" class="wysihtml5-colors-title" href="#" data-color="silver">Silver</a></li>
                                    <li><div data-wysihtml5-command-value="gray" class="wysihtml5-colors"></div><a data-wysihtml5-command-value="gray" data-wysihtml5-command="foreColor" class="wysihtml5-colors-title" href="#" data-color="grey">Gray</a></li>
                                    <li><div data-wysihtml5-command-value="maroon" class="wysihtml5-colors"></div><a data-wysihtml5-command-value="maroon" data-wysihtml5-command="foreColor" class="wysihtml5-colors-title" href="#" data-color="maroon">Maroon</a></li>
                                    <li><div data-wysihtml5-command-value="red" class="wysihtml5-colors"></div><a data-wysihtml5-command-value="red" data-wysihtml5-command="foreColor" class="wysihtml5-colors-title" href="#" data-color="red">Red</a></li>
                                    <li><div data-wysihtml5-command-value="purple" class="wysihtml5-colors"></div><a data-wysihtml5-command-value="purple" data-wysihtml5-command="foreColor" class="wysihtml5-colors-title" href="#" data-color="purple">Purple</a></li>
                                    <li><div data-wysihtml5-command-value="green" class="wysihtml5-colors"></div><a data-wysihtml5-command-value="green" data-wysihtml5-command="foreColor" class="wysihtml5-colors-title" href="#" data-color="green">Green</a></li>
                                    <li><div data-wysihtml5-command-value="olive" class="wysihtml5-colors"></div><a data-wysihtml5-command-value="olive" data-wysihtml5-command="foreColor" class="wysihtml5-colors-title" href="#" data-color="olive">Olive</a></li>
                                    <li><div data-wysihtml5-command-value="navy" class="wysihtml5-colors"></div><a data-wysihtml5-command-value="navy" data-wysihtml5-command="foreColor" class="wysihtml5-colors-title" href="#" data-color="navy">Navy</a></li>
                                    <li><div data-wysihtml5-command-value="blue" class="wysihtml5-colors"></div><a data-wysihtml5-command-value="blue" data-wysihtml5-command="foreColor" class="wysihtml5-colors-title" href="#" data-color="blue">Blue</a></li>
                                    <li><div data-wysihtml5-command-value="aqua" class="wysihtml5-colors"></div><a data-wysihtml5-command-value="aqua" data-wysihtml5-command="foreColor" class="wysihtml5-colors-title" href="#" data-color="aqua">Aqua</a></li>
                                    <li><div data-wysihtml5-command-value="fuchsia" class="wysihtml5-colors"></div><a data-wysihtml5-command-value="fuchsia" data-wysihtml5-command="foreColor" class="wysihtml5-colors-title" href="#" data-color="fuchsia">Fuchsia</a></li>
                                    <li><div data-wysihtml5-command-value="lime" class="wysihtml5-colors"></div><a data-wysihtml5-command-value="lime" data-wysihtml5-command="foreColor" class="wysihtml5-colors-title" href="#" data-color="lime">Lime</a></li>
                                    <li><div data-wysihtml5-command-value="teal" class="wysihtml5-colors"></div><a data-wysihtml5-command-value="teal" data-wysihtml5-command="foreColor" class="wysihtml5-colors-title" href="#" data-color="teal">Teal</a></li>
                                    <li><div data-wysihtml5-command-value="white" class="wysihtml5-colors"></div><a data-wysihtml5-command-value="white" data-wysihtml5-command="foreColor" class="wysihtml5-colors-title" href="#" data-color="white">White</a></li>
                                    <li><div data-wysihtml5-command-value="yellow" class="wysihtml5-colors"></div><a data-wysihtml5-command-value="yellow" data-wysihtml5-command="foreColor" class="wysihtml5-colors-title" href="#" data-color="yellow">Yellow</a></li>
                                </ul>
                            </span>

                            <div class="btn-group">
                                <a data-wysihtml5-command="bold" class="btn btn-small" href="#" title="Bold">B</a>
                                <a data-wysihtml5-command="italic" class="btn btn-small" href="#" title="Italiv">I</a>
                                <a data-wysihtml5-command="underline" class="btn btn-small" href="#" title="Underlined">U</a>
                            </div>
                            <div class="btn-group">
                                <a title="Unordered list" data-wysihtml5-command="insertUnorderedList" class="btn btn-small" href="#" ><i class="icon-list"></i></a>
                                <a title="Ordered list" data-wysihtml5-command="insertOrderedList" class="btn btn-small" href="#" ><i class="icon-th-list"></i></a>
                                <a title="Indent" data-wysihtml5-command="Indent" class="btn btn-small" href="#" ><i class="icon-indent-right"></i></a>
                                <a title="Outdent" data-wysihtml5-command="Outdent" class="btn btn-small" href="#" ><i class="icon-indent-left"></i></a>
                            </div>

                            <a title="Insert link" data-wysihtml5-command="createLink" class="btn btn-small" href="#" ><i class="icon-link"></i></a>
                            <a title="Insert image" data-wysihtml5-command="insertImage" class="btn btn-small" href="#" ><i class="icon-picture"></i></a>
                            <a title="View text/source" data-wysihtml5-action="change_view" class="btn btn-small" href="#" ><i class="icon-retweet"></i></a>

                            <div data-wysihtml5-dialog="createLink" style="display: none;">
                              <label>
                                Link:
                                <input data-wysihtml5-dialog-field="href" value="http://">
                              </label>
                              <a data-wysihtml5-dialog-action="save">OK</a>
                              <a data-wysihtml5-dialog-action="cancel">Cancel</a>
                            </div>

                            <div data-wysihtml5-dialog="insertImage" style="display: none;">
                              <label>
                                Image:
                                <input data-wysihtml5-dialog-field="src" value="http://">
                              </label>
                              <label>
                                Align:
                                <select data-wysihtml5-dialog-field="className">
                                  <option value="">default</option>
                                  <option value="wysiwyg-float-left">left</option>
                                  <option value="wysiwyg-float-right">right</option>
                                </select>
                              </label>
                              <a data-wysihtml5-dialog-action="save">OK</a>
                              <a data-wysihtml5-dialog-action="cancel">Cancel</a>
                            </div>
                        </div>
                        <textarea rows="20" name="content" id="article-content" placeholder="Article text" class="span10"></textarea>
                    </div>
                    <div class="buttons clearfix">
                        <button type="submit" id="submit" class="btn btn-xlarge btn-tales-one">Modifier l'article</button>
                    </div>
                </form>
            </aside>
            <script src="js/wysihtml5.parser.js"></script>
            <script src="js/wysihtml5.min.js"></script>
            <script>
                var editor = new wysihtml5.Editor("article-content", {
                    toolbar: "toolbar",
                    useLineBreaks: true,
                    stylesheets: "css/wysihtml5.css",
                    parserRules: wysihtml5ParserRules
                });

                $('#font-change a').click(function()
                {
                    $('.current-font').text($(this).text());
                });

                $('#privacy-change a').click(function(event)
                {
                    event.preventDefault();
                    $('#current-privacy').html($(this).text()+' <b class="caret"></b>');
                    $('#privacy').attr('value', $(this).attr('data-value'));
                });

                $('#category-change a').click(function(event)
                {
                    event.preventDefault();
                    $('#current-category').html($(this).text()+' <b class="caret"></b>');
                    $('#category_id').attr('value', $(this).attr('data-value'));
                });

                $('#color-change a').click(function()
                {
                    $('#color-change a').removeClass('wysihtml5-command-active');
                    $(this).addClass('wysihtml5-command-active');
                    $('.current-color').text($(this).text());
                    $('#color-tint').css('color', $(this).attr('data-color'));
                });

                $(document).ready(function()
                {
                    $('.file').preimage();
                    editor.on("load", function()
                    {
                        editor.setValue("<?php echo addslashes($article['content']); ?>", true);
                    });
                });
            </script>
            <style>
                .prev_thumb { max-width:1500px; height:1500px;}

                #toolbar a i{font-size: 14px;line-height: 15px !important;}
                #article-content {margin-top: 5px;}
                a[data-wysihtml5-command="bold"] {font-weight: bold!important;}
                a[data-wysihtml5-command="italic"] {font-style: italic!important;}
                a[data-wysihtml5-command="underline"] {text-decoration: underline!important;}

                div.wysihtml5-colors {display: block;height: 20px;margin-left: 5px;margin-top: 2px;pointer-events: none;position: absolute;width: 50px;}
                a.wysihtml5-colors-title {margin-left: 60px;}

                div[data-wysihtml5-command-value="black"] {background: none repeat scroll 0 0 black !important;}
                div[data-wysihtml5-command-value="silver"] {background: none repeat scroll 0 0 silver !important;}
                div[data-wysihtml5-command-value="gray"] {background: none repeat scroll 0 0 gray !important;}
                div[data-wysihtml5-command-value="maroon"] {background: none repeat scroll 0 0 maroon !important;}
                div[data-wysihtml5-command-value="red"] {background: none repeat scroll 0 0 red !important;}
                div[data-wysihtml5-command-value="purple"] {background: none repeat scroll 0 0 purple !important;}
                div[data-wysihtml5-command-value="green"] {background: none repeat scroll 0 0 green !important;}
                div[data-wysihtml5-command-value="olive"] {background: none repeat scroll 0 0 olive !important;}
                div[data-wysihtml5-command-value="navy"] {background: none repeat scroll 0 0 navy !important;}
                div[data-wysihtml5-command-value="blue"] {background: none repeat scroll 0 0 blue !important;}
                div[data-wysihtml5-command-value="aqua"] {background: none repeat scroll 0 0 aqua !important;}
                div[data-wysihtml5-command-value="fuchsia"] {background: none repeat scroll 0 0 fuchsia !important;}
                div[data-wysihtml5-command-value="lime"] {background: none repeat scroll 0 0 lime !important;}
                div[data-wysihtml5-command-value="teal"] {background: none repeat scroll 0 0 teal !important;}
                div[data-wysihtml5-command-value="white"] {background: none repeat scroll 0 0 white !important;}
                div[data-wysihtml5-command-value="yellow"] {background: none repeat scroll 0 0 yellow !important;}

                .wysihtml5-command-active {font-weight: bold;  background-color: #D9D9D9;background-image: none;box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15) inset, 0 1px 2px rgba(0, 0, 0, 0.05);outline: 0 none;}
                [data-wysihtml5-dialog] {margin: 5px 0 0;padding: 5px;border: 1px solid #666;}
            </style>
        </div>
    </div>
</div>