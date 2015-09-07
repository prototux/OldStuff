var canvas, ctx;
var image;
var iMouseX, iMouseY = 1;
var theSelection;
var type = 'title';

//Selection area...
function Selection(x, y, w, h){
	this.x = x;
	this.y = y;
	this.w = w;
	this.h = h;

	this.px = x;
	this.py = y;

	this.csize = 6;
	this.csizeh = 10

	this.bDragAll = false;
}

//Draw the selection (stroke rectangle and blit part of original image)
Selection.prototype.draw = function()
{
	ctx.strokeStyle = '#1E80CC';
	ctx.lineWidth = 2;
	ctx.strokeRect(this.x, this.y, this.w, this.h);

	if (this.w > 0 && this.h > 0)
		ctx.drawImage(image, this.x, this.y, this.w, this.h, this.x, this.y, this.w, this.h);
}

//Generate the canvas (blit image, blit a translucent black area, blit the selector)
function drawScene()
{
	ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);

	//Blit original image + 0.6A black (to make it darker)
	ctx.drawImage(image, 0, 0, ctx.canvas.width, ctx.canvas.height);
	ctx.fillStyle = 'rgba(0, 0, 0, 0.6)';
	ctx.fillRect(0, 0, ctx.canvas.width, ctx.canvas.height);

	//Blit selection
	theSelection.draw();
}

(function( $ )
{
	var settings = {
		'scale': 'contain',
		'prefix': 'prev_',
		'types': ['image/gif', 'image/png', 'image/jpeg'],
		'mime': {'jpe': 'image/jpeg', 'jpeg': 'image/jpeg', 'jpg': 'image/jpeg', 'gif': 'image/gif', 'png': 'image/png', 'x-png': 'image/png', 'tif': 'image/tiff', 'tiff': 'image/tiff'}
	};

	var methods = {
		init: function(options)
		{
			settings = $.extend(settings, options);

			return this.each(function()
			{
				$(this).bind('change', methods.change);
				$('#'+settings['prefix']+this.id).html('').addClass(settings['prefix']+'container');
			});
		},

		destroy: function()
		{
			return this.each(function()
			{
				$(this).unbind('change');
			})
		},

		base64_encode: function(data)
		{
			var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
			var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
			ac = 0,
			enc = "",
			tmp_arr = [];

			if (!data)
				return data;

			do { // pack three octets into four hexets
				o1 = data.charCodeAt(i++);
				o2 = data.charCodeAt(i++);
				o3 = data.charCodeAt(i++);

				bits = o1 << 16 | o2 << 8 | o3;

				h1 = bits >> 18 & 0x3f;
				h2 = bits >> 12 & 0x3f;
				h3 = bits >> 6 & 0x3f;
				h4 = bits & 0x3f;

				// use hexets to index into b64, and append result to encoded string
				tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
			} while (i < data.length);

			enc = tmp_arr.join('');
			var r = data.length % 3;
			return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
		},

		change: function(event)
		{
			var id = this.id
			$('#'+settings['prefix']+id).html('');

			if(window.FileReader)
			{
				for(i=0; i<this.files.length; i++)
				{
					if(!$.inArray(this.files[i].type, settings['types']) == -1)
					{
						window.alert("File of not allowed type");
						return false
					}
				}

				for(i=0; i<this.files.length; i++)
				{
					var reader = new FileReader();
					reader.onload = function (e)
					{
						// loading source image
						image = new Image();
						image.onload = function ()
						{
							if (image.width < 800 || image.height < 375)
								$('#file-message').text('L\'image n\'est pas assez grande, il faut une image d\'au moins 800*600px');
							else
							{
								// creating canvas and context objects
								canvas = document.getElementById('panel');
								ctx = canvas.getContext('2d');

								//If the image is too big, set it to 1000*y (to keep ratio),
								//then, overwrite the image with the smaller one (to avoid selection bug)
								if (image.height > window.innerHeight || image.width > 1000)
								{
									ctx.canvas.height = image.height * (1000/image.width);
									ctx.canvas.width = 1000;
									ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
									ctx.drawImage(image, 0, 0, ctx.canvas.width, ctx.canvas.height);
									image.src = ctx.canvas.toDataURL("image/png");
								}
								else
								{
									ctx.canvas.height = image.height;
									ctx.canvas.width = image.width;
								}

								theSelection = new Selection((ctx.canvas.width-183)/2, (ctx.canvas.height-183)/2, 183, 183);
								drawScene();
								$('#selectBtn').fadeIn('fast');
								$('#panel').fadeIn('fast');
							}
						}
						image.src = e.target.result;
					};
					reader.readAsDataURL(this.files[i]);
				}
			}
			else if(window.confirm('Internet Explorer do not support required HTML5 features. \nPleas, download better browser - Firefox, Google Chrome, Opera... \nDo you want to download and install Google Chrome now?'))
					window.location("//google.com/chrome");
		}
	};

	$.fn.preimage = function( method )
	{
	if (methods[method])
		return methods[method].apply(this, Array.prototype.slice.call( arguments, 1));
	else if (typeof method === 'object' || ! method)
		return methods.init.apply(this, arguments);
	else
		$.error('Method '+method+'does not exist');
	};
})( jQuery );


$('#panel').mousemove(function(e)
{
	var canvasOffset = $(canvas).offset();
	iMouseX = Math.floor(e.pageX - canvasOffset.left);
	iMouseY = Math.floor(e.pageY - canvasOffset.top);

	if (theSelection.bDragAll)
	{
		if (iMouseX - theSelection.px >= 0 && iMouseX - theSelection.px + theSelection.w <= ctx.canvas.width)
			theSelection.x = iMouseX - theSelection.px;
		if (iMouseY - theSelection.py >= 0 && iMouseY - theSelection.py + theSelection.h <= ctx.canvas.height)
			theSelection.y = iMouseY - theSelection.py;
	}
	drawScene();
});

$('#panel').mousedown(function(e)
{
	var canvasOffset = $(canvas).offset();
	iMouseX = Math.floor(e.pageX - canvasOffset.left);
	iMouseY = Math.floor(e.pageY - canvasOffset.top);

	theSelection.px = iMouseX - theSelection.x;
	theSelection.py = iMouseY - theSelection.y;

	if (iMouseX > theSelection.x + theSelection.csizeh && iMouseX < theSelection.x+theSelection.w - theSelection.csizeh &&
		iMouseY > theSelection.y + theSelection.csizeh && iMouseY < theSelection.y+theSelection.h - theSelection.csizeh)
		theSelection.bDragAll = true;
});

$('#panel').mouseup(function(e)
{
	theSelection.bDragAll = false;
	theSelection.px = 0;
	theSelection.py = 0;
});

$('#selectBtn').click(function(event)
{
	event.preventDefault();
	var temp_ctx, temp_canvas;
	temp_canvas = document.createElement('canvas');
	temp_ctx = temp_canvas.getContext('2d');
	temp_canvas.width = theSelection.w;
	temp_canvas.height = theSelection.h;
	temp_ctx.drawImage(image, theSelection.x, theSelection.y, theSelection.w, theSelection.h, 0, 0, theSelection.w, theSelection.h);
	var vData = temp_canvas.toDataURL('image/jpg');
	if (type == 'title')
	{
		$('#crop_result_title').attr('src', vData);
		$('#image_title').attr('value', vData);
		theSelection = new Selection(0, 20, ctx.canvas.width, 300);
		drawScene();
		$('#crop_result_title').fadeIn('fast');
		type = 'header'
	}
	else if (type == 'header')
	{
		$('#crop_result_header').attr('src', vData);
		$('#image_header').attr('value', vData);
		$('#crop_result_title').hide(function()
		{
			$('#crop_result_header').show();
		});
		$('#selectBtn').fadeOut('fast', function()
		{
			$('#croppedControls').fadeIn('fast');
		});
		$('#panel').fadeOut('fast');
	}
});

$('#toggleTitle').click(function(event)
{
	event.preventDefault();
	if ($('#crop_result_header').is(':visible'))
	{
		$('#crop_result_header').hide(function()
		{
			$('#crop_result_title').show();
		});
	}
	else if ($('#crop_result_title').is(':visible'))
		$('#crop_result_title').hide();
	else
		$('#crop_result_title').show();
})

$('#toggleHeader').click(function(event)
{
	event.preventDefault();
	if ($('#crop_result_title').is(':visible'))
	{
		$('#crop_result_title').hide(function()
		{
			$('#crop_result_header').show();
		});
	}
	else if ($('#crop_result_header').is(':visible'))
		$('#crop_result_header').hide();
	else
		$('#crop_result_header').show();
})
