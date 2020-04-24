local format = {}

format.name = "Linux terminal config"
format.extension = ".sh"

function format.color(color, utils)
	return utils.rgb2lhex(color)
end

return format
