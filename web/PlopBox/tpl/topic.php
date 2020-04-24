<div class="container">
	<div class="row">
		<div class="span12 header">
			<h4>Forum</h4>
		</div>
		<div class="span4">
			<div class="message-sidebar">
					<?php
						foreach ($forums as $forum)
						{
							echo '<a href="forum.php?id='.$forum['id'].'" class="message-preview new '.(($forum['id'] == $forumid)?' forum-active':'').'">';
							echo '<h4>'.$forum['name'].'</h4>';
							echo '<h5 class="sub">'.$forum['description'].'</h5>';
							echo '</a>';
						}
					?>
			</div>
		</div>

		<div class="span8">
			<div class="messages">
				<h4 class="header"><?php echo $thread['title']; ?><?php if ($thread['creator_id'] == $_SESSION['id']) echo ' // <a href="forum.php?id='.$thread['category_id'].'&deleteTopic='.$thread['id'].'">Supprimer</a>'; ?></h4>
				<?php
					foreach ($messages as $message)
					{
						echo '<div class="message">';
						echo '<div class="message-body">';
						echo '<p class="pull-right">'.date('d/m/Y à h:m', $message['date']).'</p>';
						echo '<h5>'.$message['user'];
						if ($message['user'] == $_SESSION['login'])
							echo ' // <a href="topic.php?id='.getVar('id').'&deleteMessage='.$message['id'].'">supprimer</a> // <a href="message.php?id='.$message['id'].'&topic='.getVar('id').'">Modifier</a>';
						echo '</h5>';
						echo '<p>'.$message['content'].'</p>';
						echo '</div>';
						echo '</div>';
					}
				?>
				<div class="message">
					<div class="message-body">
						<form action="topic.php?id=<?php echo getVar('id') ?>" method="post">
							<textarea name="message" placeholder="Repondre"></textarea><br/><input type="submit" class="btn" value="Envoyer" />
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
