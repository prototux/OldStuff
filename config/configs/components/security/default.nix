{config, pkgs, lib, ...}:
let
   cfg = config.components.security;
in with lib;
{
	options = {
		components.security = {
			enable = mkOption {
				default = false;
				type = with types; bool;
				description = ''
					Trying to get secure, and getting a red flag from the NSA.
				'';
			};
		};
	};

	config = mkIf cfg.enable {
		environment.systemPackages = with pkgs; [
			gnupg
			sudo
		];
	};
}
