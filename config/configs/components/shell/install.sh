#!/bin/sh

# Install ZSH Config
echo "$CONFIGT"
cp $CONFIG/zsh/rc /home/$USERNAME/.zshrc
mkdir -p /home/$USERNAME/.config/zsh/
cp $CONFIG/zsh/prompt /home/$USERNAME/.config/zsh/prompt
