local format = {}

format.name = "Conemu XML file"
format.extension = ".xml"

function format.color(color, utils)
	return utils.rgb2lhex(color):gsub("#", "")
end

return format
