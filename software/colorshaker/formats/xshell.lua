local format = {}

format.name = "Xshell config file"
format.extension = ".xcs"

function format.color(color, utils)
	return utils.rgb2lhex(color):gsub("#", "")
end

return format
