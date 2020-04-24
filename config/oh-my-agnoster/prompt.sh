# Customized powerline-like prompt
# Based on Oh-my-zsh's Agnoster theme and Oh-my-git, with heavy customization

CURRENT_BG='NONE'

# Begin a segment
prompt_segment() {
	local bg="%K{$1}" fg="%F{$2}"
	[[ $CURRENT_BG != 'NONE' && $1 != $CURRENT_BG ]] && echo -n " %{$bg%F{$CURRENT_BG}%}%{$fg%} " || echo -n "%{$bg%}%{$fg%} "
	CURRENT_BG=$1
	echo -n $3
}

# End the line, closing any open segments
prompt_end() {
	[[ -n $CURRENT_BG ]] && echo -n " %{%k%F{$CURRENT_BG}%}" || echo -n "%{%k%}"
	[[ $1 == true ]] && echo "%{%f%}" || echo -n "%{%f%}"
	CURRENT_BG='NONE'
}

# Git informations, printed only in a git repo
prompt_git() {
    local current_commit_hash=$(git rev-parse HEAD 2> /dev/null)

    if [[ -n $current_commit_hash ]]; then
        local current_branch=$(git rev-parse --abbrev-ref HEAD 2> /dev/null)
		local local_prompt="┇"
		local commit_prompt=""
		local remote_prompt=""

		# Getting more infos if the repo have log
	if [[ $(git log --pretty=oneline -n1 2> /dev/null | wc -l) -ne 0 ]]; then
		local upstream=$(git rev-parse --symbolic-full-name --abbrev-ref @{upstream} 2> /dev/null)
		local git_status="$(git status --porcelain 2> /dev/null)"
		local tag=$(git describe --exact-match --tags $current_commit_hash 2> /dev/null)

		[[ $git_status =~ ($'\n'|^).M ]] && local_prompt+=" ●"
		[[ $git_status =~ ($'\n'|^)M ]] && local_prompt+=" "
		[[ $git_status =~ ($'\n'|^)A ]] && local_prompt+=" ✚"
		[[ $git_status =~ ($'\n'|^).D ]] && local_prompt+=" "
		[[ $git_status =~ ($'\n'|^)D ]] && local_prompt+=" "
		[[ $git_status =~ ($'\n'|^)[MAD] && ! $git_status =~ ($'\n'|^).[MAD\?] ]] && local_prompt+=" "
		[[ $(\grep -c "^??" <<< "${git_status}") -gt 0 ]] && local_prompt+=" " 
		[[ $(git stash list -n1 2> /dev/null | wc -l) -gt 0 ]] && local_prompt+=" "

            if [[ -n "$upstream" ]]; then
                local commits_diff="$(git log --pretty=oneline --topo-order --left-right ${current_commit_hash}...${upstream} 2> /dev/null)"
                local commits_ahead=$(\grep -c "^<" <<< "$commits_diff")
                local commits_behind=$(\grep -c "^>" <<< "$commits_diff")
            fi
        fi

		# Commits part
		[[ -z $upstream ]] && commit_prompt="--   --"
		[[ $commits_ahead -gt 0 && $commits_behind -gt 0 ]] && commit_prompt="-${commits_behind}  +${commits_ahead}"
		[[ $commits_ahead -eq 0 && $commits_behind -gt 0 ]] && commit_prompt="-${commits_behind} ▼ --"
		[[ $commits_ahead -gt 0 && $commits_behind -eq 0 ]] && commit_prompt="-- ▲ +${commits_ahead}"

		# Branch/Upstream part
        if [[ -z "$upstream" ]]; then
			remote_prompt="$current_branch"
        else
			[[ $(git config --get branch.${current_branch}.rebase 2> /dev/null) == true ]] && symbol="" || symbol=""
			remote_prompt="${current_branch} ${symbol} ${upstream//\/$current_branch/}"
        fi
		[[ -n $tag ]] && remote_prompt+="$tag"

		# Print the git prompt
		prompt_segment green black $local_prompt
		[[ -n $commit_prompt ]] && prompt_segment black white $commit_prompt
		[[ $current_branch == 'HEAD' ]] && prompt_segment red white " (${current_commit_hash:0:7})" || prompt_segment blue black $remote_prompt
		prompt_end true
	fi
}

# Create the prompt
prompt_reg() {
	[[ $1 -ne 0 && $1 -ne 148 ]] && prompt_segment red default ""
	[[ $(jobs -l | wc -l) -gt 0 ]] && prompt_segment yellow black ""
	[[ $UID -eq 0 ]] && prompt_segment black default "%{%F{yellow}%}⚡ %m" || prompt_segment black default "%m"
	prompt_segment blue black '%~'
	prompt_end
}

build_prompt() {
	prompt_git
	prompt_reg $1
}

PROMPT='%{%f%b%k%}$(build_prompt $?) '
