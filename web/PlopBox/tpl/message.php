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
							echo '<a href="forum.php?id='.$forum['id'].'" class="message-preview new">';
							echo '<h4>'.$forum['name'].'</h4>';
							echo '<h5 class="sub">'.$forum['description'].'</h5>';
							echo '</a>';
						}
					?>
			</div>
		</div>
		<div class="span8">
			<div class="messages">
				<h4 class="header">Modifier un message</h4>
				<div class="message">
					<div class="message-body">
						<form action="topic.php?id=<?php echo getVar('topic') ?>&editMessage=<?php echo getVar('id'); ?>" method="post">
							<textarea style="height: 400px" name="message"><?php echo strip_tags($message['content']); ?></textarea><br/><input type="submit" class="btn" value="Envoyer" />
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
