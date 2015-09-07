<div class="widewrapper main">
    <div class="container">
        <div class="row">
            <div class="span10">
                <h2>Make a gallery</h2>
                <form action="#" method="post" accept-charset="utf-8">
                    <div class="controls controls-row">
                        <input type="text" name="title" id="title" placeholder="Nom de la gallerie" class="span5" />
                        <span class="dropdown" style="margin-left: 5px;">
                            <a href="#" data-toggle="dropdown" class="btn btn-large dropdown-toggle" id="current-privacy">Public <b class="caret"></b></a>
                            <ul class="dropdown-menu" id="privacy-change">
                                <li><a data-value="0">Public</a></li>
                                <li><a data-value="1">Private</a></li>
                            </ul>
                            <input type="hidden" id="privacy" name="privacy" value="0" />
                        </span>
                    </div>

                    <div class="controls">
                        <input type="text" name="description" id="description" placeholder="Description" class="span10" />
                    </div>
                    <div class="controls">
                        Pictures upload will be in the next page
                    </div>
                    <div class="buttons clearfix">
                        <button type="submit" class="btn btn-xlarge btn-tales-one">Save</button>
                    </div>
                </form>
	            <script>
	                $('#privacy-change a').click(function(event)
	                {
	                    event.preventDefault();
	                    $('#current-privacy').html($(this).text()+' <b class="caret"></b>');
	                    $('#privacy').attr('value', $(this).attr('data-value'));
	                });
	            </script>
            </div>
        </div>
    </div>
</div>