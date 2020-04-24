local format = {}

format.name = "XFCE4 terminal config"
format.extension = ".rc"

function format.color(color, utils)
	return utils.rgb2lhex16(color)
end

return format
