{config, pkgs, lib, ...}:
let
   cfg = config.components.messaging;
in with lib;
{
	options = {
		components.messaging = {
			enable = mkOption {
				default = false;
				type = with types; bool;
				description = ''
					IM/Mail software & configuration.
				'';
			};
		};
	};

	config = mkIf cfg.enable {
		environment.systemPackages = with pkgs; [
			profanity
			weechat
		];
	};
}
