<div class="widewrapper main">
    <div class="container">
        <div class="row">
            <div class="span10">
                <h2>Upload pictures</h2>
                <form action="addgallery.php" method="post" accept-charset="utf-8">
                    <div class="controls">
                        <input id="fileupload" type="file" class="file filestyle" data-classButton="btn btn-large btn-upload" data-buttonText="Add a picture" data-input="false" data-classIcon="icon-plus" multiple>
                        <br />
                        <table class="table table-striped" role="presentation">
                            <tbody class="files">
                            </tbody>
                        </table>
                        <canvas id="resizer" style="display: none;"></canvas>
                    </div>
                    <div class="buttons clearfix">
                        <input type="hidden" name="gallery_id" value="<?php echo $galleryId; ?>" />
                        <button type="submit" class="btn btn-xlarge btn-tales-one">Finish</button>
                    </div>
                </form>

                <script>
                    var fileNumber = 0;

                    $("#fileupload").change(function ()
                    {
                        console.log($(this));
                        var reader = new FileReader();
                        reader.onload = function(e)
                        {
                            console.log(e);
                            fileNumber++;
                            $('.files').append('<tr class="file_element span10" id="file_'+fileNumber+'"><td style="width: 200px;"><canvas id="preview_'+fileNumber+'"></canvas></td><td><p class="name">'+$('#fileupload').val()+'</p></td><td><p class="size">'+bytesToSize($('#fileupload')[0].files[0].size,2)+'</p></td><td class="upcontrols_'+fileNumber+'"><div class="progress progress-striped active"><div class="bar" style="width: 0%;"></div></div>​</td></tr>');
                            $('td').attr('style', 'width: 200px;');

                            var img = new Image();
                            var bigimg;
                            img.onload = function()
                            {
                                var rcanvas = document.getElementById('resizer');
                                if (img.width > 1280 || img.height > 720)
                                {
                                    if (img.width > img.height && img.width > 1280)
                                    {
                                        rcanvas.height = img.height*(1280/img.width);
                                        rcanvas.width = 1280;
                                    }
                                    else if (img.height > 720)
                                    {
                                        rcanvas.width = img.width * (720/img.height);
                                        rcanvas.height = 720;
                                    }
                                    var ctx = rcanvas.getContext("2d");
                                    ctx.drawImage(img, 0, 0, rcanvas.width, rcanvas.height);
                                    bigimg = rcanvas.toDataURL();
                                }

                                var canvas = document.getElementById('preview_'+fileNumber);
                                if (img.width > img.height && img.width > 200)
                                {
                                    canvas.height = img.height*(200/img.width);
                                    canvas.width = 200;
                                }
                                else if (img.height > 150)
                                {
                                    canvas.width = img.width*(150/img.height);
                                    canvas.height = 150;
                                }
                                var ctx = canvas.getContext("2d");
                                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                                var xhr = new XMLHttpRequest();
                                var fd = new FormData();

                                xhr.upload.addEventListener("progress", function(e)
                                {
                                    if (e.lengthComputable)
                                    {
                                        var percentage = Math.round((e.loaded * 100) / e.total)*2;
                                        $('.bar').width(percentage);
                                    }
                                }, false);

                                xhr.upload.addEventListener("load", function(e)
                                {
                                    $('.progress').removeClass('active');
                                    $('.upcontrols_'+fileNumber).html('<button class="btn btn-large btn-danger delete-picture" data-picture="'+fileNumber+'">Delete</button>')

                                    $('.delete-picture').click(function (event)
                                    {
                                        event.preventDefault();;
                                        var number = $(this).attr('data-picture');
                                        $.post("upimage.php", { 'delete_picture' : number, 'key': '<?php echo UPLOAD_KEY; ?>', 'gallery_id': <?php echo $galleryId; ?>}, function(data)
                                        {
                                            if (data == 'OK')
                                                $('#file_'+number).fadeOut('slow');
                                        });
                                    });
                                }, false);
                                fd.append('gallery_id', <?php echo $galleryId; ?>);
                                fd.append('file_number', fileNumber);
                                fd.append('data', rcanvas.toDataURL("image/jpg"));
                                fd.append("key", "<?php echo UPLOAD_KEY; ?>");
                                xhr.open("POST", "upimage.php");
                                xhr.send(fd);
                            }
                            img.src = e.target.result;
                        }
                        reader.readAsDataURL($(this)[0].files[0]);
                    });

                    function bytesToSize(bytes, precision)
                    {
                        var kilobyte = 1024;
                        var megabyte = kilobyte * 1024;
                        var gigabyte = megabyte * 1024;
                        var terabyte = gigabyte * 1024;

                        if ((bytes >= 0) && (bytes < kilobyte))
                            return bytes + ' B';
                        else if ((bytes >= kilobyte) && (bytes < megabyte))
                            return (bytes / kilobyte).toFixed(precision) + ' KB';
                        else if ((bytes >= megabyte) && (bytes < gigabyte))
                            return (bytes / megabyte).toFixed(precision) + ' MB';
                        else if ((bytes >= gigabyte) && (bytes < terabyte))
                            return (bytes / gigabyte).toFixed(precision) + ' GB';
                        else if (bytes >= terabyte)
                            return (bytes / terabyte).toFixed(precision) + ' TB';
                        else
                            return bytes + ' B';
                    }
                </script>
                <style>
                    .bcontainer {
                        width: 100px;
                    }​

                    td {
                        max-width: 200px;
                    }​
                </style>
            </div>
        </div>
    </div>
</div>