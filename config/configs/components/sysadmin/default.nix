{config, pkgs, lib, ...}:
let
   cfg = config.components.sysadmin;
in with lib;
{
	options = {
		components.sysadmin = {
			enable = mkOption {
				default = false;
				type = with types; bool;
				description = ''
					Given some rumors, that's my job, hum...
				'';
			};
		};
	};

	config = mkIf cfg.enable {
		environment.systemPackages = with pkgs; [
			tcpdump
			wireshark
			nmap
		];
	};
}
