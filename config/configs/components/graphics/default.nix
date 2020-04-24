{config, pkgs, lib, ...}:
let
   cfg = config.components.graphics;
in with lib;
{
	options = {
		components.graphics = {
			enable = mkOption {
				default = false;
				type = with types; bool;
				description = ''
					A sysadmin drawing stuff, oh god, don't want to see that.
				'';
			};
		};
	};

	config = mkIf cfg.enable {
		environment.systemPackages = with pkgs; [
			gimp
		];
	};
}
