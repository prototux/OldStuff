{config, pkgs, lib, ...}:
let
   cfg = config.components.scripts;
in with lib;
{
	options = {
		components.scripts = {
			enable = mkOption {
				default = false;
				type = with types; bool;
				description = ''
					Custom scripts for random, mostly stupid things.
				'';
			};
		};
	};

	config = mkIf cfg.enable {
	};
}
