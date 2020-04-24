{config, pkgs, lib, ...}:
let
   cfg = config.components.shell;
in with lib;
{
	options = {
		components.shell = {
			enable = mkOption {
				default = false;
				type = with types; bool;
				description = ''
					Trying to get that dusty thing that is TTYs as comfortable as possible.
				'';
			};
		};
	};

	config = mkIf cfg.enable {
		environment.systemPackages = with pkgs; [
			zsh
			wget
			git
		];

		# Enable zsh as a login shell
		programs.zsh.enable = true;

		system.activationScripts.shellConfig =
			if config.components.user.enable then
				stringAfter ["users"] ''
					export CONFIG=${./config}
					export USERNAME=${config.components.user.name}
					/bin/sh ${./install.sh}
				''
			else stringAfter ["users"] '' '';
	};
}
