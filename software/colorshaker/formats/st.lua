local format = {}

format.name = "st config.h"
format.extension = ".h"

function format.color(color, utils)
	return utils.rgb2lhex(color)
end

return format
