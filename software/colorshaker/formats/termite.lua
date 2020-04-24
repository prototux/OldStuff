local format = {}

format.name = "Termite config file"
format.extension = ".config"

function format.color(color, utils)
	return utils.rgb2lhex(color)
end

return format
