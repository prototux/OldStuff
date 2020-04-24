{config, pkgs, lib, ...}:
let
   cfg = config.components.wifi;
in with lib;
{
	options = {
		components.wifi = {
			enable = mkOption {
				default = false;
				type = with types; bool;
				description = ''
					Wifi stuff, cancer maybe included.
				'';
			};
		};
	};

	config = mkIf cfg.enable {
		networking.wireless.enable = true;
	};
}
