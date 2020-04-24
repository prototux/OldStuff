<div class="container">
	<div class="row">
		<div class="span12 header"><a href="#topicModal" data-toggle="modal" class="btn pull-right">Nouveau topic</a>
			<h4>Forum</h4>
		</div>
		<div class="span4">
			<div class="message-sidebar">
				<?php
					foreach ($forums as $forum)
					{
						echo '<a href="forum.php?id='.$forum['id'].'" class="message-preview new'.(($forum['id'] == $forumid)?' forum-active':'').'">';
						echo '	<h4>'.$forum['name'].'</h4>';
						echo '	<h5 class="sub">'.$forum['description'].'</h5>';
						echo '</a>';
					}
				?>
			</div>
		</div>

		<div class="span8">
			<div class="message-sidebar">
				<?php
					foreach ($threads as $thread)
					{
						echo '<a href="topic.php?id='.$thread['id'].'" class="message-preview new">';
						echo '<h4>'.$thread['title'].'</h4>';
						echo '<p>Par '.$thread['creator'].' // Dernier message par '.$thread['lastUser'].' le '.date('d/m/Y à h:m', $thread['lastDate']).'</p>';
						echo '</a>';
					}
				?>
			</div>
		</div>
	</div>
</div>
<div id="topicModal" class="modal hide fade">
	<form class="form-horizontal" action="forum.php?id=<?php echo getVar('id'); ?>" method="post" style="margin-bottom: 0px;">
		<div class="modal-header">
				<button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
				<h3>Nouveau topic</h3>
		</div>
		<div class="modal-body">
			<div class="control-group">
				<label for="inputTitle" class="control-label" style="width: 50px;">Titre </label>
				<div class="controls" style="margin-left: 60px;">
					<input id="inputTitle" name="title" type="text" placeholder="Le titre du topic" style="width:450px"/>
				</div>
			</div>
			<div class="control-group">
				<label for="inputCurrentPassword" class="control-label" style="width: 50px;">Message </label>
				<div class="controls" style="margin-left: 60px;">
					<textarea style="width: 450px; height: 200px" id="inputMessage" name="message" placeholder="Le message du topic"></textarea>
				</div>
			</div>
		</div>
		<div class="modal-footer"><a href="#" data-dismiss="modal" class="btn">Fermer</a><input type="submit" value="Envoyer" class="btn btn-primary"></div>
	</form>
</div>
