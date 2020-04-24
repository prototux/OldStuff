local format = {}

format.name = "Guake gconf script"
format.extension = ".sh"

function format.color(color, utils)
	return utils.rgb2lhex16(color)
end

return format
