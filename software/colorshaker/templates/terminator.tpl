[global_config]
  title_transmit_fg_color = "{{>foreground}}"
  title_transmit_bg_color = "{{>background}}"
  title_inactive_fg_color = "{{>foreground}}"
  title_inactive_bg_color = "{{>background}}"

[profiles]
  [[default]]
    palette = "{{>0}}:{{>1}}:{{>2}}:{{>3}}:{{>4}}:{{>5}}:{{>6}}:{{>7}}:{{>8}}:{{>9}}:{{>10}}:{{>11}}:{{>12}}:{{>13}}:{{>14}}:{{>15}}"
    foreground_color = "{{>foreground}}"
    background_color = "{{>background}}"
    cursor_color = "{{>cursor}}"

[layouts]
  [[default]]
    [[[child1]]]
      type = Terminal
      parent = window0
      profile = default
    [[[window0]]]
      type = Window
      parent = ""
[plugins]
