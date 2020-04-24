-- Local ROMs collection
local files = {}

local data = {}

function files.load(path)
	local files = {}
	for filename in lfs.dir(path) do
		if filename ~= "." and filename ~= ".." then
			local file = {}
			file.name = filename
			file.size = lfs.attributes(path.."/"..filename).size

			-- QnD function to escape a parameter and execute command on that parsm
			function moreattrs(cmd)
				local raw = io.popen(cmd.." '"..path.."/"..string.gsub(filename, "%'", "'\"'\"'").."'", "r"):read("*a")
				return raw:match("^(.-)%s+.*$")
			end
			file.crc = moreattrs("crc32")
			file.md5 = moreattrs("md5sum")
			file.sha1 = moreattrs("sha1sum")
			table.insert(data, file)
		end
	end
end

function files.getAll()
	return data
end

return files
