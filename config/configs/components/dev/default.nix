{config, pkgs, lib, ...}:
let
   cfg = config.components.development;
in with lib;
{
	options = {
		components.development = {
			enable = mkOption {
				default = false;
				type = with types; bool;
				description = ''
					Stuff that i need to write bugs.
				'';
			};
		};
	};

	config = mkIf cfg.enable {
		environment.systemPackages = with pkgs; [
		];
	};
}
