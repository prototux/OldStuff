local format = {}

format.name = "Xorg resource file"
format.extension = ".xresources"

function format.color(color, utils)
	return utils.rgb2lhex(color)
end

return format
